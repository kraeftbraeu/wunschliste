#!/bin/bash

docker ps -a | grep mysql:5.7 | cut -d " " -f1 | xargs -I {} docker rm {}