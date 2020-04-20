#!/bin/sh

# Start scheduling
sudo launchctl load -w /Library/LaunchDaemons/net.xgallery.schedule.plist

# Start Supervisor
brew services start supervisor