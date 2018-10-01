#!/usr/bin/env bash

git pull -q
if [ $(git status | grep "Changes" -c) -gt 0 ]; then
    echo "Commit changes first!"
    exit 1
fi

git checkout -b temp_build
if [ -f /package/bin/build ]; then
    /package/bin/build
fi
git add -A
git commit -m "Build"
