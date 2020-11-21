#!/bin/bash

list_descendants ()
{
  local children=$(ps -o pid= --ppid "$1")

  for pid in $children
  do
    list_descendants "$pid"
  done

  echo "$children"
}

sudo kill -9 $(list_descendants $1) > /dev/null 2>&1
