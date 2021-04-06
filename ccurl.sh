#!/bin/bash

TEMPDIR=$(mktemp -d)
PUBKEY=$(readlink -f pubkey)
cd $TEMPDIR

curl --fail -D headers -o out $@ || exit -2
dos2unix headers

# Validate the checksum
grep Digest headers | cut -c '17-'  | base64 -d -i | od -t x1 -w32 -A none | sed -e 's/ //g' | sed -e 's/$/ out/' > out.check
sha256sum -c out.check 1>&2 || exit -1

# Compute the string that was signed. Only support "date digest"
grep  Date headers | sed -e 's/^Date/date/' > lestring
echo -n $(grep  Digest headers | sed -e 's/^Digest/digest/') >> lestring

# Check the signature...
grep -o -P '(?<=signature=")[^"]*' headers  | base64 -d > signature
openssl dgst  -sha256 -verify "${PUBKEY}" -signature signature lestring 1>&2 || exit -1

# Verified OK
echo "This replied was signed on $(grep Date headers)" 1>&2
cat out


