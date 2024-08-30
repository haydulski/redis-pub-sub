# Redis pub/sub demo

## Quick start
- git clone project
- docker compose up -d
- open 2 terminals
- 1 terminal tab: docker exec -it {pub image name} sh
- 2 terminal tab: docker exec -it {sub image name} sh
- sub terminal tab: php index.php 
- pub terminal tab: php index.php 

### Useful Redis commands
- redis-cli (open redis cli)
- publish messages "message 1"
- subscribe messages
- unsubscribe messages
- pubsub channels * (check all active publishers/subscribers)
- flushdb

## Redis streams quick start
- git fetch -all
- git checkout redis-stream
- git pull
- docker compose up --build -d

### Useful Redis stream commands
- xrange {stream name} {start timestamp} {end timestamp}
- xread count 2 streams {stream name} 0 (2 stands for limit to 2 items, 0 stands for id of item greater than 0)