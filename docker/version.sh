#!/usr/bin/env bash

major()
{
    echo $(echo $1 | cut -f1 -d.)
}

minor()
{
    echo $(echo $1 | cut -f2 -d.)
}

patch()
{
    echo $(echo $1 | cut -f3 -d.)
}

newer()
{
    [ $(patch $2) -lt $(patch $1) ] && new_patch=1 || new_patch=0
    [ $(minor $2) -lt $(minor $1) ] && new_minor=1 || new_minor=$new_patch
    [ $(major $2) -lt $(major $1) ] && new=1 || new=$new_minor
    [ $new -eq 1 ] && echo $1 || echo $2
}

latest()
{
    newest=0.0.0
    for tag in $(git tag); do
        [ -z "$newest" ] && newest=$tag || newest=$(newer $tag $newest)
    done

    echo $newest
}
