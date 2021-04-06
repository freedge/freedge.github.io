Tests hosting of static pages on github.io

A pipeline also push that as "pages perso" on free.fr for various experimentations.

https://github.com/microsoft/azure-pipelines-tasks/issues/14676 was opened in
the process due to the behavior of the FTP server of free.fr

Since free.fr runs on PHP5.6, and does not provide https, we workaround by using "HTTP signatures" from draft: 
https://tools.ietf.org/html/draft-cavage-http-signatures-11

We use https://github.com/liamdennehy/http-signatures-php/ that supports PHP
5.6, but we revert to an older commit so to be able to use the native OpenSSL
instead of a SLOW phpseclib.

Signature can be verified (but.. not by curl). Check ccurl.sh

