<?php

namespace Artworch\Http\Controllers\Production;

use Artworch\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage, Zipper, File;

class ProductionController extends Controller
{

    /**
     * Show form of uploading new project
     *
     * @return Response
     */
    public function uploadShow()
    {
        // show form
        return view('account.production.upload');
    }


    /**
     * Save data about user's new product in storage and database
     *
     * @param Request $request
     * @return Response
     */
    public function uploadSend(Request $request)
    {
        // * validate
        // условия:
        //          - имена обоих полей должны быть эквивалентны _project и _receive
        //          - оба поля должны быть обязательными для заполнения
        //          - только архивы формата zip, rar;
        //          - тип текстового поля = только десятичное число

        $file = $request->file('_project');
        $fileExt = $file->extension(); // .zip
        $fileHash = pathinfo($file->hashName(), PATHINFO_FILENAME); // dkajsdjadw419fds

        /**
         * физическое сохранение загруженного архива
         */
        // создать папку с этим именем

        Storage::makeDirectory('production/requests/' . $fileHash);
        // переместить в нее архив
        $filePath = storage_path('app/') . Storage::putFile('production/requests/' . $fileHash, $file);
        // распаковать все содержимое:
        //                            - папка с проектом;
        //                            - превью для админов;
        //                            - блокнот с данными о типе визуализации;
        Zipper::make($filePath)->extractTo(storage_path('/app/production/requests/' . $fileHash));
        Zipper::close();
        // удалить архив
        Storage::delete('production/requests/' . $fileHash . '/' . $fileHash . '.' . $fileExt);

        /**
         * сохранение информации о заявке в базу данных
         */
        // id автора, наименование папки архива, себестоимость, дата отправки заявки


        return redirect()->route('account.production.requests_show');
    }

    /**
     * Show user's production list of requests
     *
     * @return Response
     */
    public function requestsShow()
    {
        return view('account.production.requests');
    }
    
}
