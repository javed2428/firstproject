# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

from scrapy.item import Item,Field
class AmazonItem(Item):
    # define the fields for your item here like:
    # name = scrapy.Field()
    title = Field()
    price = Field()
    img_src = Field()
    link = Field()
    tech_details = Field()
    prod_desc = Field()
    asin = Field()
    brnd = Field()