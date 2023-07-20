#!/bin/bash
while true; do
inotifywait -e modify,create,delete -r ../trangtinvietnam
php watch.php
done       
