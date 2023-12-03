#!/usr/bin/env bash

while [ true ]
do
  php /app/linky schedule:run --verbose --no-interaction &
  sleep 60
done

