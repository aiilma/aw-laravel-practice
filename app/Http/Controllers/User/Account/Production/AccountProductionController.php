<?php

namespace Artworch\Http\Controllers\User\Account\Production;

use Artworch\Http\Controllers\Controller;
use Artworch\Modules\User\Account\CompRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Storage, Zipper;

class AccountProductionController extends Controller
{

    /**
     * Show form of uploading new project
     *
     * @return Response
     */
    public function showUploader()
    {
        // show form
        return view('systems.user.account.production.upload');
    }

    /**
     * Save data about user's new product in storage and database
     *
     * @param Request $request
     * @return Response
     */
    public function sendCompRequest(\Artworch\Http\Requests\SendCompRequest $request)
    {
        $response = array(
            'messages' => [
                'steam' => [],
                'transaction' => [],
            ],
        );

        // Валидация на лимит по количеству продукции текущего пользователя
        $response['messages']['transaction'] = auth()->user()->validateOnSendProductionRequest();
        // Если есть сообщения, то отправить их пользователю
        if (count($response['messages']['transaction']) !== 0)
        {
            return redirect()->back()->withErrors($response['messages']['transaction']);
        }


        $projectRequest = CompRequest::initializeData($request);


        // Распаковать архив в X директорию
        Zipper::make($projectRequest['archive']['absolute'])->extractTo($projectRequest['dir']['absolute']);
        Zipper::close();
        Storage::delete($projectRequest['archive']['relative']);


        // Валидация на наличие конфиг файла проекта пользователя
        $response['messages']['transaction'] = CompRequest::validateOnExistingConfigFile($projectRequest);
        // Если есть сообщения, то отправить их пользователю
        if (count($response['messages']['transaction']) !== 0)
        {
            Storage::deleteDirectory($projectRequest['dir']['relative']);   
            return redirect()->back()->withErrors($response['messages']['transaction']);
        }


        // Считать содержимое конфига...
        $projectRequest['config']['json'] = file_get_contents($projectRequest['config']['absolute']);
        

        // Проверить файл на корректность json формата прежде чем декодить в массив
        $response['messages']['transaction'] = CompRequest::validateUserConfigOnJSON($projectRequest['config']);
        // Если есть сообщения, то отправить их пользователю
        if (count($response['messages']['transaction']) !== 0)
        {
            Storage::deleteDirectory($projectRequest['dir']['relative']);
            return redirect()->back()->withErrors($response['messages']['transaction']);
        }
        

        // Преобразовать json в массив
        $projectRequest['config']['assoc'] = json_decode($projectRequest['config']['json'], true);



        // Валидация данных конфига по стандартизированным секциям и ключам...
        $response['messages']['transaction'] = CompRequest::validateUserConfigOnFormat($projectRequest['config']);
        // Если есть сообщения, то отправить их пользователю
        if (count($response['messages']['transaction']) !== 0)
        {
            Storage::deleteDirectory($projectRequest['dir']['relative']);
            return redirect()->back()->withErrors($response['messages']['transaction']);
        }

        // Изменение имен файлов на hash.ext_file подобные
        Storage::move($projectRequest['dir']['relative'] . '/' . $projectRequest['config']['assoc']['demo']['picturePathes']['freeze'], $projectRequest['dir']['relative'] . '/' . $projectRequest['hash'] . '.png');
        Storage::move($projectRequest['dir']['relative'] . '/' . $projectRequest['config']['assoc']['demo']['picturePathes']['preview'], $projectRequest['dir']['relative'] . '/' . $projectRequest['hash'] . '.gif');
        
        // Создать запись в БД
        CompRequest::addToDatabase($projectRequest);

        // Отправить пользователя на страницу списка заявок...
        return redirect()->route('acc-prod-showrequests');
    }

    /**
     * Show user's production list of requests
     *
     * @return Response
     */
    public function showRequests(Request $request)
    {
        dd(auth()->user()->compRequests);
        // return view('user.account.production.requests');
    }
    

    public function showList()
    {
        dd(auth()->user()->compositions);
        // 
    }
}
