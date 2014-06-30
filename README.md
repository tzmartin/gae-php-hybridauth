# Google App Engine PHP HybridAuth

### Demo: [http://gae-php-hybridauth.appspot.com](http://gae-php-hybridauth.appspot.com)

[![Try it](https://api.monosnap.com/image/download?id=KtKvM8bPxPaRfylOax9HiI2Ji9oyta)](http://gae-php-hybridauth.appspot.com)

This release makes [HybridAuth](http://hybridauth.sourceforge.net) compatible with the GAE PHP interpreter and enables you to offer users multiple sign in options for your GAE application, not just Google Accounts.

### Design Goal

The main goal is to act as an abstract API between your application and various social apis and identities providers (such as Facebook, Twitter or Github), while still leveraging the native User APIs on App Engine and Google's single sign on capabilities.

Previously, HybridAuth did not play well with GAE due to GAE's lack of support for cUrl and the decoupling of HybridAuth's authentication flow with the native GAE User APIs.

This fork addresses these concerns while introducing a unified model layer for authentication and profile normalization.

### Features:

- Leverages Memcache for session management.
- Fixes the cUrl limitation by leveraging ```file_get_contents``` (see [Purl-GAE](http://github.com/tzmartin/purl-gae) project)
- Adds a model class for normalizing logic, sessions and user data between the native Google Accounts and HybridAuth.
- Adds helper methods for simpler data access across all social adapters and GAE's auth.

You can leverage a single API for authentication that follows GAE's native implementation.

**Example:**

```
require_once 'Model.User.php';

$User = new UserModel;

// Create auth URL
$url = $User->createLoginUrl($_GET['provider'],'/auth?provider='.$_GET['provider']);
      
// Start authentication flow
$User->authenticate( $_GET['provider'], array('hauth_return_to' => $url) );
```

## Install

If using composer..

```
composer install
```

If old school, download the standalone [hybridauth-gae](https://github.com/tzmartin/hybridauth-gae) library from Github and put in your project.

### License

The MIT License (MIT)

Copyright (c) 2014 Terry Z Martin

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

