<?php

namespace app\modules\admin\controllers;

use app\models\Category;
use app\models\UploadFile;
use Yii;
use app\models\Book;
use app\models\search\BookSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Book models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=5;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Book();

        if($model->load(Yii::$app->request->post())) {
            $this->uploadBooksFiles($model);

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post())) {
            $this->uploadBooksFiles($model);

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSetCategory()
    {
        if(Yii::$app->request->isPost) {
            $cat = Yii::$app->request->post('cat');
            $arSelection = Yii::$app->request->post('selection');

            if(!empty($cat) && !empty($arSelection)) {
                $objCategory = Category::findOne($cat);

                foreach($arSelection as $idBook) {
                    $objBook = Book::findOne($idBook);
                    $objBook->link('category', $objCategory);
                }

                Yii::$app->session->setFlash('success', "Books have been moved!");
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * @param string $id
     * @param string $file pdf|epub|txt|doc|fb2
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelFile($id, $file)
    {
        $uploadFile = new UploadFile();
        $model = $this->findModel($id);

        //of course we can check the existence of a model property, but we are in admin part, i don't think that
        // admin will hack application
        if($file == 'preview') {
            $path = $model->getFullPathOfPreview();
            $uploadFile->deleteFile($path);
            $model->preview = '';
        } else {
            $path = $model->getFullPathOfFile($file);
            $uploadFile->deleteFile($path);
            $model->$file = NULL;
        }

        $model->save();

        return $this->redirect(['update', 'id'=>$id]);
    }

    protected function uploadBooksFiles(&$model)
    {
        $uploadImage = new UploadFile();

        $file = UploadedFile::getInstance($model, 'filePreview');

        if($file !== null && !empty($file)) {
            $uploadedPreviewName = $uploadImage->uploadFile($file, $model->preview, $model::FOLDER_FOR_IMAGE, '', 0777);
            $model->preview = $uploadedPreviewName;
        }

        $filePdf = UploadedFile::getInstance($model, 'filePdf');
        if($filePdf !== null && !empty($filePdf)) {
            $uploadedFileName = $uploadImage->uploadFile($filePdf, $model->pdf, '', $model::FOLDER_FOR_FILES, 0777);
            $model->pdf = $uploadedFileName;
        }

        $fileEpub = UploadedFile::getInstance($model, 'fileEpub');
        if($fileEpub !== null && !empty($fileEpub)) {
            $uploadedFileName = $uploadImage->uploadFile($fileEpub, $model->epub, '', $model::FOLDER_FOR_FILES, 0777);
            $model->epub = $uploadedFileName;
        }
        $fileTxt = UploadedFile::getInstance($model, 'fileTxt');
        if($fileTxt !== null && !empty($fileTxt)) {
            $uploadedFileName = $uploadImage->uploadFile($fileTxt, $model->txt, '', $model::FOLDER_FOR_FILES, 0777);
            $model->txt = $uploadedFileName;
        }
        $fileFb2 = UploadedFile::getInstance($model, 'fileFb2');
        if($fileFb2 !== null && !empty($fileFb2)) {
            $uploadedFileName = $uploadImage->uploadFile($fileFb2, $model->fb2, '', $model::FOLDER_FOR_FILES, 0777);
            $model->fb2 = $uploadedFileName;
        }
        $fileDoc = UploadedFile::getInstance($model, 'fileDoc');
        if($fileDoc !== null && !empty($fileDoc)) {
            $uploadedFileName = $uploadImage->uploadFile($fileDoc, $model->doc, '', $model::FOLDER_FOR_FILES, 0777);
            $model->doc = $uploadedFileName;
        }
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
