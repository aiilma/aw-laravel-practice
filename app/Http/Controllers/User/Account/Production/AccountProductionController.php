<?php

namespace Artworch\Http\Controllers\User\Account\Production;

use Artworch\Http\Controllers\Controller;
use Artworch\Modules\User\Account\CompRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Storage, File, Auth, Validator, Zipper;

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
        return view('user.account.production.upload');
    }

    /**
     * Save data about user's new product in storage and database
     *
     * @param Request $request
     * @return Response
     */
    public function sendCompRequest(\Artworch\Http\Requests\SendCompRequest $request)
    {
        // Валидация на лимит по количеству продукции текущего пользователя
        Validator::make([
                'userProdCount' => Auth::user()->compRequests->count(),
            ],
            [
                'userProdCount' => 'lte:4'
            ],
            [
                'userProdCount.lte' => 'You can\'t send requests anymore. Max count of any production activity for your account (including your products on sale) is equals to 4',
        ])->validate();

        $projectFile = $request->file('_project');
        $receive = $request->_receive;
        $project = [
            'hash' => pathinfo($projectFile->hashName(), PATHINFO_FILENAME), // dkajsdjadw419fds,
            'receive' => $receive,
        ];

        // Создать директорию X, где имя папки проекта - сгенерированный токен из имени файла
        $project['dir']['relative'] = 'compositions/production/requests/' . $project['hash'];
        $project['dir']['absolute'] = storage_path('app/') . $project['dir']['relative'];
        $project['archive']['relative'] = $projectFile->store($project['dir']['relative']);
        $project['archive']['absolute'] = storage_path('app/') . $project['archive']['relative'];
        $project['config']['relative'] = $project['dir']['relative'] . '/aw-config.json';
        $project['config']['absolute'] = storage_path('app/') . $project['config']['relative'];

        // Распаковать архив в X директорию
        Zipper::make($project['archive']['absolute'])->extractTo($project['dir']['absolute']);
        Zipper::close();
        Storage::delete($project['archive']['relative']);

        // Считать содержимое конфига...
        $project['config']['json'] = file_get_contents($project['config']['absolute']);
        
        // Проверить файл на корректность json формата прежде чем декодить в массив
        $validatorOfConfigFile = Validator::make($project['config'], [
            'json' => 'required|json',
            ],
            [
            'json.required' => 'Please, make sure the project\'s config file is exists and it\'s no empty string',
            'json.json' => 'Your config file must have a valid json format',
        ]);


        if ($validatorOfConfigFile->fails())
        {
            Storage::deleteDirectory($project['dir']['relative']);            
            return redirect()->back()->withErrors($validatorOfConfigFile);
        }
        

        // Преобразовать json в массив
        $project['config']['assoc'] = json_decode($project['config']['json'], true);

        // Валидация данных конфига по стандартизированным секциям и ключам...
        $validatorOfConfigContent = Validator::make($project['config'], [
                'assoc' => 'size:3',

                'assoc.interface' => 'required|array|size:2',
                'assoc.demo' => 'required|array|size:1',
                'assoc.projectFolder' => 'required|string',

                'assoc.interface.projectName' => 'required|string|between:3, 32',
                'assoc.interface.visualizationType' => 'required|integer|between:0, 1',

                'assoc.demo.picturePathes' => 'required|array|size:2',
                'assoc.demo.picturePathes.freeze' => 'required|string',
                'assoc.demo.picturePathes.preview' => 'required|string',

                'assoc.projectFolder' => 'required|string',
            ],
            [
                'assoc.size' => 'Your config file must contain only 3 sections',
                // Sections...
                'assoc.interface.required' => 'The interface section is required',
                'assoc.interface.array' => 'The interface section must contain sub-sections in associations with JSON format',
                'assoc.interface.size' => 'The interface section must contain only 2 sub-sections',
                'assoc.demo.required' => 'The demo section is required',
                'assoc.demo.array' => 'The demo section must contain a sub-section in associations with JSON format',
                'assoc.demo.size' => 'The demo section must contain only 1 sub-section',
                'assoc.projectFolder.required' => 'The projectFolder section is required',
                'assoc.projectFolder.string' => 'The projectFolder section\'s value must be a string',

                // Sub-sections of interface section...
                'assoc.interface.projectName.required' => 'The projectName\'s value is required (interface)',
                'assoc.interface.projectName.string' => 'The projectName\'s value must be a string (interface)',
                'assoc.interface.projectName.between' => 'The projectName\'s value must be between 3 and 32 characters (interface)',
                'assoc.interface.visualizationType.required' => 'The visualizationType\'s value is required (interface)',
                'assoc.interface.visualizationType.integer' => 'The visualizationType\'s value must be an integer (interface)',
                'assoc.interface.visualizationType.between' => 'The visualizationType\'s value must be 0 or 1 (interface)',
                // Sub-sections of demo section...
                'assoc.demo.picturePathes.required' => 'The picturePathes is required (demo)',
                'assoc.demo.picturePathes.array' => 'The picturePathes must contain keys in associations with JSON format (demo)',
                'assoc.demo.picturePathes.size' => 'The picturePathes must contain only 2 keys (demo)',
                'assoc.demo.picturePathes.freeze.required' => 'The freeze\'s value is required (demo - picturePath)',
                'assoc.demo.picturePathes.freeze.string' => 'The freeze\'s value must be a string (demo - picturePath)',
                'assoc.demo.picturePathes.preview.required' => 'The preview\'s value is required (demo - picturePath)',
                'assoc.demo.picturePathes.preview.string' => 'The preview\'s value must be a string (demo - picturePath)',
                // Name of project folder
                'assoc.projectFolder.required' => 'The projectFolder\'s value is required',
                'assoc.projectFolder.string' => 'The projectFolder\'s value must be a string',


        ]);
        

        if ($validatorOfConfigContent->fails()) {
            Storage::deleteDirectory($project['dir']['relative']);
            return redirect()->back()->withErrors($validatorOfConfigContent);
        }


        // Изменение имен файлов на hash.ext_file подобные
        Storage::move($project['dir']['relative'] . '/' . $project['config']['assoc']['demo']['picturePathes']['freeze'], $project['dir']['relative'] . '/' . $project['hash'] . '.png');
        Storage::move($project['dir']['relative'] . '/' . $project['config']['assoc']['demo']['picturePathes']['preview'], $project['dir']['relative'] . '/' . $project['hash'] . '.gif');
        

        // Создать запись в БД
        $compRequest = new CompRequest;
        $compRequest->title = $project['config']['assoc']['interface']['projectName'];
        $compRequest->custom_price = $project['receive'];
        $compRequest->visualization = $project['config']['assoc']['interface']['visualizationType'];
        $compRequest->project_token = $project['hash'];
        $compRequest->author_id = Auth::user()->id;
        $compRequest->save();

        // Отправить пользователя на страницу списка заявок...
        return redirect()->route('acc-prod-showrequests');
    }

    /**
     * Show user's production list of requests
     *
     * @return Response
     */
    public function showRequests()
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
