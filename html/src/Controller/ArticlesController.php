<?php

namespace App\Controller;

use App\Controller\AppController;

class ArticlesController extends AppController
{
  public function initialize(): void
  {
    parent::initialize();

    $this->loadComponent('Flash'); // Flashコンポーネントを含める
  }

  public function index()
  {
    $this->set('articles', $this->Articles->find()->all());
  }

  public function view($id)
  {
    $article = $this->Articles->get($id);
    $this->set(compact('article'));
  }

  public function add()
  {
    $article = $this->Articles->newEmptyEntity();
    if ($this->request->is('post')) {
      // 3.4.0 より前は $this->request->data() が使われました。
      $article = $this->Articles->patchEntity($article, $this->request->getData());
      if ($this->Articles->save($article)) {
        $this->Flash->success(__('記事が保存されました。'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('記事の保存が出来ませんでした。'));
    }
    $this->set('article', $article);
  }

  public function edit($id = null)
  {
    $article = $this->Articles->get($id);
    if ($this->request->is(['post', 'put'])) {
      $this->Articles->patchEntity($article, $this->request->getData());
      if ($this->Articles->save($article)) {
        $this->Flash->success(__('記事が更新されました。'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('記事の更新ができませんでした。'));
    }

    $this->set('article', $article);
  }

  public function delete($id)
  {
    $this->request->allowMethod(['post', 'delete']);

    $article = $this->Articles->get($id);
    if ($this->Articles->delete($article)) {
      $this->Flash->success(__('ID:{0}の記事が削除されました。', h($id)));
      return $this->redirect(['action' => 'index']);
    }
  }
}
