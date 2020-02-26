#!/bin/bash

docker ps -a | grep nginx:1.13.8 | cut -d " " -f1 | xargs -I {} docker rm {}