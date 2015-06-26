# -*- coding: utf-8 -*-

# Scrapy settings for amazon project
#
# For simplicity, this file contains only the most important settings by
# default. All the other settings are documented here:
#
#     http://doc.scrapy.org/en/latest/topics/settings.html
#

BOT_NAME = 'amazon'

SPIDER_MODULES = ['amazon.spiders']
NEWSPIDER_MODULE = 'amazon.spiders'

# from scrapy import log
#
# ITEM_PIPELINES = [
#     'scrapyelasticsearch.scrapyelasticsearch.ElasticSearchPipeline',
# ]
#
# ELASTICSEARCH_SERVER = 'localhost' # If not 'localhost' prepend 'http://'
# ELASTICSEARCH_PORT = 9200 # If port 80 leave blank
# ELASTICSEARCH_USERNAME = ''
# ELASTICSEARCH_PASSWORD = ''
# ELASTICSEARCH_INDEX = 'scrapy'
# ELASTICSEARCH_TYPE = 'items'
# ELASTICSEARCH_UNIQ_KEY = 'link'
# ELASTICSEARCH_LOG_LEVEL= log.DEBUG


# Crawl responsibly by identifying yourself (and your website) on the user-agent
#USER_AGENT = 'amazon (+http://www.yourdomain.com)'
