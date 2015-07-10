import scrapy
import urlparse
from amazon.items import AmazonItem


class MySpider(scrapy.Spider):
    name = 'amazy'
    allowed_domains = ['amazon.in']
    start_urls = ['http://www.amazon.in/s/ref=sr_nr_n_1?fst=as%3Aoff&rh=n%3A1375458031%2Ck%3Atablets&keywords=tablets&ie=UTF8&qid=1436173981&rnid=3576079031']

    def parse(self, response):
        res_arr = response.xpath('//li[@class="s-result-item"]')
        for res in res_arr:
            item = AmazonItem()
            link_arr = res.xpath('.//a[@class="a-link-normal a-text-normal"]/@href')
            link = link_arr[0].extract()
            item['link'] = link
            item['title'] = res.xpath(
                './/h2[@class="a-size-base a-color-null s-inline s-access-title a-text-normal"]/text()').extract()[0]
            item['img_src'] = res.xpath('.//img[@class="s-access-image cfMarker"]/@src').extract()[0]
            item['price'] = res.xpath(
                './/span[@class="a-size-base a-color-price s-price a-text-bold"]/text()').extract()
            item[
                'tech_details'] = ""  # only for checking if item['tech_details'] is empty or not after tech_details callback is returned(see if item['tech_details'] below)
            request = scrapy.Request(link, callback=self.tech_details)
            request.meta['item'] = item
            yield request
            if item['tech_details'] != "":
                yield item

        href = response.xpath('//a[@id="pagnNextLink"]/@href').extract()[0] #urlparse is for making absolute path(i.e. url) form relative path(i.e. href)
        url = urlparse.urljoin(response.url, href)
        yield scrapy.Request(url, callback=self.parse)

    def tech_details(self, response):

        feature_map = {}
        tech_det = response.xpath('//div[@class="pdTab"]')[0]
        rows = tech_det.xpath('.//tr')
        for row in rows:
            key = row.xpath('./td[1]/text()').extract()[0]
            value = row.xpath('./td[2]/text()').extract()[0]
            feature_map[key] = value
        item = response.meta['item']
        item['tech_details'] = feature_map
        item['prod_desc'] = response.xpath('.//div[@class="productDescriptionWrapper"]/text()').extract()[0]
        res = response.xpath('.//div[@class="section techD"]')[1]
        item['asin'] = res.xpath('.//td/text()').extract()[1]
        item['brnd'] = response.xpath('.//a[@id="brand"]/text()').extract()[0]
        return item