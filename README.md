Tests hosting of static pages on github.io

A pipeline also push that as "pages perso" on free.fr for various experimentations.

https://github.com/microsoft/azure-pipelines-tasks/issues/14676 was opened in
the process due to the behavior of the FTP server of free.fr

Since free.fr runs on PHP5.6, and does not provide https, we workaround by using "HTTP signatures" from draft: 
https://tools.ietf.org/html/draft-cavage-http-signatures-11

We use https://github.com/liamdennehy/http-signatures-php/ that supports PHP
5.6, but we revert to an older commit so to be able to use the native OpenSSL
instead of a SLOW phpseclib.

Signature can be verified (but.. not by curl)

```
curl -D headers -o out.json ${SITE}/web/sign/sign.php
dos2unix headers

# Checking the checksum. can't be much uglier
grep Digest headers | cut -c '17-'  | base64 -d -i | od -t x1 -w32 -A none | sed -e 's/ //g' | sed -e 's/$/ out.json/' > out.json.check
sha256sum -c out.json.check
# return out.json: OK

# Public key should be there already. If not:
cat out.json | jq -r .pubkey > pubkey.pem
openssl x509 -in pubkey.pem -noout -pubkey -out pubkey

# Compute the string that was signed
grep  Date headers | sed -e 's/^Date/date/' > lestring
grep  Digest headers | sed -e 's/^Digest/digest/' >> lestring
# need to remove a new line a the end of that file

# Retrieve the signature...
grep -o -P '(?<=signature=")[^"]*' headers  | base64 -d > signature

# And finally!
openssl dgst  -sha256 -verify pubkey -signature signature lestring 
# Verified OK
```

Still need to check the date + URL + method used though.

