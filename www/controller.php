<?php
/**
 * RAMP - Rapid web application development environment for building flexible, customisable web systems.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if
 * not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @author Matt Renyard (renyard.m@gmail.com)
 * @package RAMP
 * @version 0.0.9;
 */
namespace ramp;

require_once('load.ini.php');
if (isset($_GET['scratch'])) { $GLOBALS["cssScratch"] = $_GET['scratch']; unset($_GET['scratch']); }
// $session = http\Session::getInstance();
// try {
//   $session->authorizeAs(model\business\LoginAccountType::REGISTERED());
// } catch (http\Unauthorized401Exception $exception) {
//   header('HTTP/1.1 401 Unauthorized');
//   $authenticationForm = new view\AuthenticationForm($exception->getMessage());
//   $authenticationForm->setModel($session->loginAccount);
//   view\WebRoot::getInstance()->render();
//   return;
// }
try {
  $request = http\Request::current();
} catch (\DomainException $exception) {
  header('HTTP/1.1 404 Not Found');
  // TODO:mrenyard: Render view - Not Found.
  print_r('<h1>Render view - Not Found.</h1>');
  print_r('<pre>' . $exception . '</pre>');
  return;
}
$view = view\ViewManager::getView($request);
if ((string)$request->modelURN !== '') {
  $MODEL_MANAGER = SETTING::$RAMP_BUSINESS_MODEL_MANAGER;
  $modelManager = $MODEL_MANAGER::getInstance();
  try {
    $model = $modelManager->getBusinessModel($request, $request->filter, $request->fromIndex);
  } catch (model\business\DataFetchException $exception) {
    if ($request->recordKey != NULL) { // No matching Record found in data storage 
      header('HTTP/1.1 404 Not Found');
      print_r('<h1>Render view - Not Found.</h1>');
      print_r('<pre>' . $exception . '</pre>');
      return;
    } elseif ($request->recordName != NULL)  { // No Records found in data storage redirect to new
      header('HTTP/1.1 307 Temporary Redirect');
      header('Location: /' . strtolower($request->recordName) . '/new/');
      return;
    }
    throw $exception;
  }
  if ($request->method === http\Method::POST()) {
    try {
      $model->validate($request->postData);
    } catch (model\business\DataExistingEntryException $exception) {
      header('HTTP/1.1 303 See Other'); // 303 is correctly used as another resource is the corrent entry for this posted data.
      header('Location: /' . strtolower($request->recordName) . '/' . strtolower(str_replace(' ', '+', $exception->getTargetKEY())) . '/');
      return;
    }
    $modelManager->updateAny();
  }
  if (((string)$request->recordKey) == 'new' && ($model->isValid)) {
    header('HTTP/1.1 303 See Other'); // The 'new' entry can now be located following post.
    header('Location: /' . str_replace(':', '/', (string)$model->id) . '/');
    return;
  }
  $view->setModel($model);
}
header("HTTP/1.0 200 Ok");
if($request->expectsFragment) {
  header('Content-Type: text/xml');
  // simulate response delay
  sleep(rand(1,6)); // TODO:mrenyard:Remove Line
  $view->render();
} else {
  view\WebRoot::getInstance()->render();
}
