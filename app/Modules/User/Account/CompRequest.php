<?php

namespace Artworch\Modules\User\Account;

use Illuminate\Database\Eloquent\Model;
use Artworch\Modules\Compositions\Composition;
use Artworch\Modules\User\User;
use Artworch\Modules\User\Account\Order;
use Illuminate\Filesystem\Filesystem;
use Validator;

class CompRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'custom_price','visualization', 'inputs',
        'project_token', 'accept_status', 'author_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Добавляет новую заявку на загрузку композиции в БД
     *
     * @return void
     */
    public static function addToDatabase($projectData)
    {
        // если данные существуют, то
            // добавить
            $compRequest = new CompRequest;
            $compRequest->title = $projectData['config']['assoc']['interface']['projectName'];
            $compRequest->custom_price = $projectData['receive'];
            $compRequest->visualization = $projectData['config']['assoc']['interface']['visualizationType'];
            $compRequest->project_token = $projectData['hash'];
            $compRequest->author_id = auth()->user()->id;

            if ($compRequest->save())
            {
                // вернуть true
                return true;
            }
            // иначе,
            //      вернуть false
            return false;
    }

    /**
     * Инициализирует и возвращает данные заявки на загрузку композиции в виде объекта
     *
     * @param [Request] $request
     * @return array
     */
    public static function initializeData($request)
    {
        $dataProject = [
            'file' => $request->file('_project'),
            'hash' => pathinfo($request->file('_project')->hashName(), PATHINFO_FILENAME), // dkajsdjadw419fds,
            'receive' => $request->_receive,
            'authorId' => auth()->user()->id,
        ];

        // Создать директорию X, где имя папки проекта - сгенерированный токен из имени файла
        $dataProject['dir']['relative'] = 'compositions/production/requests/' . $dataProject['hash'];
        $dataProject['dir']['absolute'] = storage_path('app/') . $dataProject['dir']['relative'];
        $dataProject['archive']['relative'] = $dataProject['file']->store($dataProject['dir']['relative']);
        $dataProject['archive']['absolute'] = storage_path('app/') . $dataProject['archive']['relative'];
        $dataProject['config']['relative'] = $dataProject['dir']['relative'] . '/aw-config.json';
        $dataProject['config']['absolute'] = storage_path('app/') . $dataProject['config']['relative'];

        return $dataProject;
    }

    /**
     * Возвращает сообщения статуса валидации
     * на наличие конфиг файла проекта пользователя в виде массива
     * 
     * @param [array] $dataProject
     * @return array
     */
    public static function validateOnExistingConfigFile($dataProject)
    {
        return Validator::make([
                    'configFile' => $dataProject['config']['absolute'],
                ], [
                    'configFile' => function ($attribute, $value, $fail) use($dataProject) {
                        if (!file_exists($value)) {

                            if(storage_path('app/'.$dataProject['dir']['relative']))
                            {
                                $fs = new Filesystem;
                                $fs->cleanDirectory(storage_path('app/'.$dataProject['dir']['relative']));
                            }
                            $fail('Configuration file does not exists');
                        }
                    },
                ])->messages();
    }

    /**
     * Возвращает сообщения статуса валидации
     * по проверке файла на корректность json формата
     *
     * @param [object] $projectConfig
     * @return array
     */
    public static function validateUserConfigOnJSON($projectConfig)
    {
        return Validator::make($projectConfig, [
                    'json' => 'required|json',
                ], [
                    'json.required' => 'Please, make sure the project\'s config file is exists and it\'s no empty string',
                    'json.json' => 'Your config file must have a valid json format',
                ])->messages();
    }

    
    /**
     * Undocumented function
     *
     * @return void
     */
    public static function validateUserConfigOnFormat($configProject)
    {
        return Validator::make($configProject, [
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
                ], [
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
                ])->messages();
    }

    /**
     * Relation to composition; one to one
     *
     * @return void
     */
    public function composition()
    {
        return $this->hasOne(Composition::class);
    }

    /**
     * Relation to user; one to many
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }


    /**
     * Relation to Order; has many orders
     *
     * @return void
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'project_ref');
    }
}
