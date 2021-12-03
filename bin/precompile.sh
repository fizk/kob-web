#!/bin/sh

BASEDIR=$(dirname $0)
cd "$BASEDIR/../"

find templates -name "*.twig" -type f | cut -d/ -f2- | php ./bin/precompile.php
