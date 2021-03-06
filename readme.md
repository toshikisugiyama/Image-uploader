# Laravel で React を使う
- MAMP で開発を行った。
- MAMP の htdocs フォルダ内にプロジェクトフォルダを配置した。
## Laravel のインストール
```
composer create-project --prefer-dist laravel/laravel プロジェクト名
```
### Laravel をインストールしたら...
#### 1. データベースを作る
##### rootでmysqlにログイン
```
mysql -u root -p
```
パスワードは `root`
```
create database データベース名;
grant all on データベース名.* to 'ユーザー名'@'localhost' identified by 'パスワード';
```
#### 2. .env の編集
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=8889
DB_DATABASE=image_uploader
DB_USERNAME=image_uploader_user
DB_PASSWORD=password
```
#### 3. app.php の編集
`'timezone'` と `'locale'` をそれぞれ `'Asia/Tokyo'` と `'ja'` に変更する。
```php:app.php
'timezone' => 'Asia/Tokyo',
'locale' => 'ja',
```
#### 4. .editorconfig の編集
```
root = true

[*]
charset = utf-8
end_of_line = lf
insert_final_newline = true
indent_style = space
indent_size = 4
trim_trailing_whitespace = true

[*.md]
trim_trailing_whitespace = false

[*.{yml,yaml,js,json,html,scss,blade.php}]
indent_size = 2
```
#### 5. ブラウザで確認する
```
php artisan serve
```
## React で見た目を表示させる

### 1. React を使えるようにする
```
php artisan preset react
npm install && npm run dev
npm run watch
```
### 2. index.blade.php を作成する
```blade.php:index.blade.php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
    </head>
    <body>
        <div id='root'></div>
        <script src="/js/app.js"></script>
    </body>
</html>
```
`welcome.blade.php` は削除する。

### 3. app.php を編集する
```js:app.js
import React from 'react'
import ReactDOM from 'react-dom'
import App from './components/app'

ReactDOM.render(
  <App />,
  document.getElementById('root')
)
```
### 4. App.js を編集する
```js:App.js
import React from 'react'
const App = () => {
    return(
        <div>
            Hello World
        </div>
    )
}
export default App
```
### 5. ルーティング
#### web.php を編集する
```php: web.php
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.+');
```
これでブラウザに `Hello World` が表示されているはず。

### 6. react-router-dom
```
npm install -S react-router-dom
```
### 7. PhotoList.js と Login.js を作成する
```js:PhotoList.js
import React from 'react'
const Login = () => {
  return(
    <div>
      <h1>Photo List</h1>
    </div>
  )
}
export default Login
```
```js:Login.js
import React from 'react'
const Login = () => {
  return(
    <div>
      <h1>Login</h1>
    </div>
  )
}
export default Login
```
### 8. App.js を編集する
```js:App.js
import React from 'react'
import PhotoList from '../components/PhotoList'
import Login from '../components/Login'
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link,
} from "react-router-dom";

const App = () => {
  return(
    <Router>
      <div>
        <nav>
          <ul>
            <li>
              <Link to="/">Photo List</Link>
            </li>
            <li>
              <Link to="/login">Login</Link>
            </li>
          </ul>
        </nav>
        <Switch>
          <Route path="/login">
            <PhotoList />
          </Route>
          <Route path="/">
            <Login />
          </Route>
        </Switch>
      </div>
    </Router>
  )
}
export default App
```

## API 用のルート
`RouteServiceProvider.php` の編集
```php:RouteServiceProvider.php
protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
```
この部分を以下のように変更
```php:RouteServiceProvider.php
protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
```
### テストコード
新しいテストケースを作成する
```
php artisan make:test RegisterApiTest
```
`RegisterApiTest.php` を編集する。
```php:RegisterApiTest.php
<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function should_create_a_new_user_and_return()
    {
        $data = [
            'name' => 'user',
            'email' => 'dummy@emali.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->json('POST', route('register'), $data);
        $user = User::first();
        $this->assertSame($data['name'], $user->name);
        $response
            ->assertStatus(200)
            ->assertJson(['name' => $user->name]);
    }
}
```
