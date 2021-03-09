<?php

namespace backend\models;

use mdm\admin\models\searchs\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public $id;
    public $username;
    public $email;
    public $status;
    public $role;

    public function rules()
    {
        return [
            [['id', 'status',], 'integer'],
            [['username', 'email', 'role'], 'safe'],
        ];
    }

    public function export($params){
        $dataProvider = $this->search($params);
        $dataProvider->query->limit(500);

        return $dataProvider;
    }

    public function search($params)
    {
        /* @var $query \yii\db\ActiveQuery */
        $class = Yii::$app->getUser()->identityClass ? : 'mdm\admin\models\User';
        $query = $class::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        if($this->role)
            $query->innerJoin('auth_assignment', 'auth_assignment.user_id=user.id')->andFilterWhere(['item_name' => $this->role]);

        return $dataProvider;
    }
}
