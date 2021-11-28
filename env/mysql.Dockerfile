FROM mysql

RUN apt-get update && apt-get -y install cron

# Add crontab file in the cron directory
ADD crontab /etc/cron.d/backup-cron

# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/backup-cron

# Apply cron job
RUN crontab /etc/cron.d/backup-cron

# Start cron service
RUN /etc/init.d/cron start

# Create the log file to be able to run tail
RUN touch /var/log/cron.log
