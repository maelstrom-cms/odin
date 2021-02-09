## 09/02/2021

### 💥 Changes

- Excluded internal errors from reports. (784fe81)
- Updated deps. (2a46f3c)

### 🐛 Bugs

- Fixing visual diff controller. (4cb79f1)

## 13/11/2020

### 💥 Changes

- Added which urls the crawler found links on to help debug. (098b65c)
- Replaced user agent with Googlebot identifier to allow through systems such as Cloudflare and made visual difference use the same setting. (ea72140)

## 12/11/2020

### 💥 Changes

- Increased visual screenshot delay to 5 seconds incase theres loading animations. (bfd1102)

## 11/11/2020

### 🐛 Bugs

- Added missing error url. (f09741f)

## 29/10/2020

### 🐛 Bugs

- Fixed uptime graph being reversed. (03a5cf0)
- Fixed issue where uptime summary would be restricted by 30 days. (b4c9b9c)
- Fixed wrong visual diff heatmap showing. (0d8a9b6)

## 19/10/2020

### 🚀 Features

- Beta visual diffs. (13700bb)

### 💥 Changes

- Updated prod builds. (8f12e34)
- Increased per page. (198ab6e)

## 08/10/2020

### 📝 Documentations

- Added event to doc. (1e055e9)

## 07/10/2020

### 🚀 Features

- Added automatic-changelog package. (9488f0e)
- Added a scheduler for visual diffs. (ea2f7a7)

## 06/10/2020

### 🚀 Features

- POC for visual diffs. (7066825)

## 17/09/2020

### 💥 Changes

- Updated horizon. (36efe12)
- Updated deps. (b73fc97)

## 25/06/2020

### 🚀 Features

- Uptime checks will now retry incase its a false positive. (b99be91)

### 💥 Changes

- Prevented storing response body on success for smaller db. (a46efbc)

### 🐛 Bugs

- Increased storage capacity of response data. (6fffdcd)

### 📝 Documentations

- No commit message. (524d5ca)
- Updated changelog. (0544d50)

## 25/05/2020

### 🚀 Features

- Added artisan command to clear the queue. (2afadb9)
- Prevented overlapping jobs so we dont get a big back log. (c1804e3)

### 💥 Changes

- Manually added job tags so can monitor them more easily. (c8ad806)
- Renamed scan:certificate to scan:certificates. (1d1ca1c)
- Uptime report time period now configurable. (2560682)
- Moved up-time caching into its own queued job for speed. (4fb400a)

### 🐛 Bugs

- Fixed bug where robot queues would not un-queue. (a2c7bfe)
- Prevented the uptime report caching from running twice. (307b33d)

## 19/05/2020

### 💥 Changes

- Upgraded dependencies. (64bc7d3)
- Increased the timeout. (3ff904c)

### 🐛 Bugs

- Caught robots exception when it doesn't exist. (a428f58)

## 09/03/2020

### 🚀 Features

- Updated to the latest maelstrom core. (f9752fb)

### 🐛 Bugs

- Fixing pagination alignment and search column. (3a3fee1)

## 03/12/2019

### 🐛 Bugs

- Potential fix for 1 second durations. (d83dba4)

## 30/10/2019

### 🐛 Bugs

- Fixed a bug where SSL expiry wouldn't update if SSL Labs failed. (615fdbb)

## 23/10/2019

### 🐛 Bugs

- Extending timeouts. (1369376)
- Prevented issue where laravel would terminate the task before guzzle timed out. (b2b3eff)

## 13/10/2019

### 🚀 Features

- Daily website crawl. (dc87848)

### 🐛 Bugs

- Added missing column. (9da72df)
- No commit message. (90f56d0)

## 12/10/2019

### 🚀 Features

- Basic page crawler added. (3f5c7b6)

### 🐛 Bugs

- No commit message. (a720ecf)
- No commit message. (b5624bd)
- No commit message. (94ef131)
- No commit message. (b817196)

### 📝 Documentations

- No commit message. (d57e22c)

## 08/10/2019

### 🚀 Features

- Added caching to uptime report. (3ffae39)

### 💥 Changes

- Allowed longer og descriptions. (e9f7f6e)

### 🐛 Bugs

- Removed legacy method. (2d3811c)
- Fix unhandled guzzle exceptions. (40b4320)
- Hidden ping endpoints until the website has been saved. (090d32c)

## 07/10/2019

### 🚀 Features

- Added ability to disable registrations. (77024c5)

### 💥 Changes

- Removed visibility of a dirty helper variable. (dbe7824)
- Used a fork of abandoned dns package to resolve bugs. (7e25dfb)

## 04/10/2019

### 🐛 Bugs

- Added missing cron column to index. (7b1fa4a)

## 03/10/2019

### 🚀 Features

- Generated production build. (2de2f41)
- Added cron monitoring. (40d114c)

### 💥 Changes

- Updated the timezone to london for a test. (18a5319)

### 🐛 Bugs

- Potential fix for thrown dns lookup error. (587e21a)
- Fixed UTC and BST bug. (5f8a047)
- Added missing delete functionality. (97b3591)

## 01/10/2019

### 💥 Changes

- Updated maelstrom core. (47a49b7)

## 03/09/2019

### 🚀 Features

- Unified the settings and monitor for a better ux. (c154817)
- Enforced ssl. (775db4c)

### 🐛 Bugs

- Removed debugging to fix emails. (66a52f2)

## 02/09/2019

### 🐛 Bugs

- Removed ttl from dns as would cause constant emails. (d3eb353)
- Fixed some generic bugs. (081422e)

## 19/08/2019

### 🚀 Features

- Beta release. (5f9f3b5)

