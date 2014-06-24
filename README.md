pretty-source
=============

Tool for prettify JSON or XML.

##Installation
```
sudo curl -o /usr/bin/prettysource -s https://raw.githubusercontent.com/mkabischev/pretty-source/master/prettysource.phar
sudo chmod +x /usr/bin/prettysource
```

##Usage

```
prettysource [-f|--format="..."] [input]
```
supported formats:
- json
- xml

###using argument
```
prettysource /path/to/file
```

###unix pipe
```
cat /path/to/file | prettysource
curl http://site.com/file.json | prettysource
```
