# Redis pub/sub demo

## Quick start
- git clone project
- git fetch --all
- docker compose up -d
- open 2 terminals
- 1 terminal tab: docker exec -it {pub image name} sh
- 2 terminal tab: docker exec -it {sub image name} sh
- sub terminal tab: php index.php 
- pub terminal tab: php index.php 

### Useful Redis commands
- redis-cli (open redis cli)
- PUBLISH {channel name} "message 1"
- SUBSCRIBE {channel name}
- UNSUBSCRIBE {channel name}
- PUBSUB CHANNELS * (check all active publishers/subscribers)
- FLUSHDB

## Redis streams quick start
- git clone project
- git fetch --all
- git checkout redis-stream
- git pull
- docker compose up --build -d
- pub terminal tab: php index.php
- sub terminal tab: php index.php --start={timestamp in milliseconds} --group={group name}

### Useful Redis stream commands
- XRANGE {stream name} {start timestamp} {end timestamp} (timestamps in milliseconds)
- XREAD COUNT 2 STREAMS {stream name} 0 (2 stands for limit to 2 items, 0 stands for id of item greater than 0)
- XINFO GROUPS {stream name}
- XPENDING {stream name} {mygroup} (get messages that were read but have status pending)
- XREADGROUP GROUP {group name} {consumer name} STREAMS {stream name} '>' (get all new messages)