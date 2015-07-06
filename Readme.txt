1. Project's root directory : cd ~javed
			      cd workspace/firstproject

2. cd amazon-crawler
   for crawling data to output.json :       scrapy crawl amazy -o ../AdditionalFilesnScripts/output.json
3. cd ../AdditionalFilesnScripts
4. Script for formatted data : php script_for_formatted_json_file.php 
5. Start Elastic Search Server :
	a.) cd ~javed
	b.) cd elasticsearch-1.6.0/bin
	c.) ./elasticsearch
6. bulk import formatted_output.json file to ES
	curl -XPOST 'localhost:9200/amazon/docs/_bulk?pretty' --data-binary @formatted_output.json
