<?php

namespace app\controllers;

use app\models\Book;
use app\models\Category;
use app\models\search\BookFrontendSearch;
use app\models\search\BookSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Show category's page
     *
     * @param integer $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionShowCategory($id)
    {
        $id = (int)$id;

        if($id > 0) {
            $category = $this->findCategoryModel($id);
            $searchModelBook = new BookSearch();
            $dataProvider = $searchModelBook->search(['category_id'=>$category->id]);
            $dataProvider->pagination->pageSize=6;

            return $this->render('category', [
                'booksProvider' => $dataProvider,
                'category' => $category
            ]);

        } else {
            $this->redirect(['index']);
        }
    }

    /**
     * Show page with book's information
     *
     * @param string $slug
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionBook($slug)
    {
        /**
         * @book Book
         */
        $book = $this->findBookModelBySlug($slug);

        return $this->render('book', [
            'model' => $book,
        ]);
    }

    public function actionBookSearchByLetter($letter)
    {
        if(preg_match('/^[\w]$/iu', $letter)) {
            $searchModel = new BookFrontendSearch();
            $dataProvider = $searchModel->search(['letter'=>$letter]);

            $dataProvider->pagination->pageSize=6;

            return $this->render('search', [
                'booksProvider' => $dataProvider,
            ]);
        } else {
            $this->redirect(['index']);
        }
    }

    public function actionBookSearchByFrase()
    {
        $string = Yii::$app->request->get('frase');
        if(preg_match('/^[\w\-\_]+$/iu', $string)) {
            $searchModel = new BookFrontendSearch();
            $dataProvider = $searchModel->search(['searchString'=>$string]);

            $dataProvider->pagination->pageSize=6;

            return $this->render('search', [
                'booksProvider' => $dataProvider,
            ]);
        } else {
            $this->redirect(['index']);
        }
    }

    /**
     * @param string $filename
     *
     * @return file
     * @throws NotFoundHttpException
     */
    public function actionSendFile($filename)
    {
        $storagePath = Yii::getAlias('@web') . Book::FOLDER_FOR_FILES;

        if (!file_exists("$storagePath/$filename")) {
            throw new \yii\web\NotFoundHttpException('The file does not exists.');
        }

        $mailTo = Yii::$app->params['adminEmail'];
        $mailFrom = Yii::$app->params['fromEmail'];
        Yii::$app->mailer->compose()
            ->setTo($mailTo)
            ->setFrom([$mailFrom => 'Mail from site'])
            ->setSubject('File '.$storagePath . DIRECTORY_SEPARATOR . $filename . ' was downloaded')
            ->setTextBody('File '.$storagePath . DIRECTORY_SEPARATOR . $filename . ' was downloaded')
            ->send();

        return Yii::$app->response->sendFile("$storagePath/$filename", $filename);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCategoryModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findBookModelBySlug($slug)
    {
        $model = Book::find()
            ->where('slug=:slug', [':slug'=>$slug])
            ->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
