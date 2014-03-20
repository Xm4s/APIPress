API Press
=

API Press is a WordPress theme that convert your WordPress site in a consumable **XML/JSON API** by taking advantage of the widely used /category/postname/ permalink structure.

How does it work
-

API Press translate categories into resources, which may contains either other resources or items (posts). Requesting a resource will return a representation of its contents while requesting a specific item will return all its properties.

Installation
-

Just put a folder with all the theme files under your `wp-content/themes` WordPress installation folder and activate the theme through the WordPress admin panel. That's it, no more fancy installation steps required.

Usage
-

While the API Press theme is active your permalink structure will be automatically changed to `/%category%/%postname%/`, the only thing you should do is choose a fitting name (like "api") for your category base. If no special is selected the default **"category"** is used.

Opening `http://your-wordpress-site.com/category-base` will now show a HTML representation of all your root resources (parentless categories) from which you will be able to visually navigate through all your data inside the resources tree.

To change the response data format use the `format` param:

* `http://your-wordpress-site.com/category-base/?format='json'` will return the data in json format
* `http://your-wordpress-site.com/category-base/?format='xml'` will return the data in xml format

JSON format examples
-

Response example for request at `http://your-wordpress-site.com/category-base/cat-1/?format=json`

```json
{
  "resources": [
    {
      "ID": "3",
      "parent": "2",
      "name": "Category 11",
      "slug": "cat-11",
      "description": "Category 11 description",
      "count": "1",
      "link":"http://your-wordpress-site.com/category-base/cat-1/cat-11/"
    },
    {
      "ID": "4",
      "parent": "2",
      "name": "Category 12",
      "slug": "cat-12",
      "description": "Category 12 description",
      "count": "1",
      "link":"http://your-wordpress-site.com/category-base/cat-1/cat-12/"
    }
  ],
  "items": [
    {
      "ID": 5,
      "link": "http://your-wordpress-site.com/cat-1/post-1/"
      "slug": "post-1",
      "title": "Post 1",
      "excerpt": "Post 1 excerpt"
    }
  ]
}
```

Response example for request at `http://your-wordpress-site.com/cat-1/post-1/?format=json`

```json
{
  "items": [
    {
      "ID": 5,
      "link": "http://your-wordpress-site.com/cat-1/post-1/",
      "slug": "post-1",
      "title": "Post 1",
      "author": "Xmas",
      "date": "2014-03-05T00:00:00+00:00",
      "tags": "Tag 1, Tag 2, Tag 3",
      "categories": "Category 1, Category 11, Category 12",
      "excerpt": "Post 1 excerpt",
      "content": "Post 1 content",
      "html-content": "<p>Post 1 content</p>\n",
      "custom-fields": [
        {
          "key": "Custom 1",
          "values": ["Value 1", "Value 11"]
        },
        {
          "key": "Custom 2",
          "values": ["Value 2"]
        }
      ],
      "attachments": [
        {
          "ID": 13,
          "title": "Attachment 1",
          "caption": "Attachment 1 caption",
          "description": "Attachment 1 description",
          "html-description": "<p>Attachment 1 description</p>\n",
          "mime-type": "application/pdf",
          "link":"http://your-wordpress-site.com/wp-content/uploads/2014/03/attachment-1.pdf"
        },
        {
          "ID": 15,
          "title": "Attachment 2",
          "caption": "Attachment 2 caption",
          "description": "Attachment 2 description",
          "html-description": "<p>Attachment 2 description</p>\n",
          "mime-type": "image/jpeg",
          "link": "http://your-wordpress-site.com/wp-content/uploads/2014/03/attachment-2.jpg",
          "width": 640,
          "height": 482,
          "sizes": [
            {
              "thumbnail":
                {
                  "width": 150,
                  "height": 150,
                  "mime-type": "image/jpeg",
                  "link": "http://your-wordpress-site.com/wp-content/uploads/2014/03/attachment-2-150x150.jpg"
                },
              "medium":
                {
                  "width": 300,
                  "height": 225,
                  "mime-type": "image/jpeg",
                  "link": "http://your-wordpress-site.com/wp-content/uploads/2014/03/attachment-2-300x225.jpg"
                }
              }
            }
          ],
        }
      ],
      "comments": [
        {
          "ID": "2",
          "author": "Xmas",
          "author-email": "jintetsu@gmail.com",
          "date": "2014-03-05T00:00:00+00:00",
          "content": "Comment 1 on post 1",
          "html-content": "<p>Comment 1 on post 1</p>\n"
        },
        {
          "ID": "3",
          "author": "Xmas",
          "author-email": "jintetsu@gmail.com",
          "date": "2014-03-05T00:00:00+00:00",
          "content": "Comment 2 on post 1",
          "html-content": "<p>Comment 2 on post 1</p>\n"
        }
      ]
    }
  ]
}
```

XML format example
-

Response example for request at `http://your-wordpress-site.com/category-base/cat-1/?format=xml`

```xml
<root>
  <resources>
    <resource key="0">
      <ID>3</ID>
      <parent>2</parent>
      <name><![CDATA[Category 11]]></name>
      <slug>cat-11</slug>
      <description><![CDATA[Category 11 description]]></description>
      <count>1</count>
      <link>http://your-wordpress-site.com/category-base/cat-1/cat-11/</link>
    </resource>
    <resource key="0">
      <ID>4</ID>
      <parent>2</parent>
      <name><![CDATA[Category 12]]></name>
      <slug>cat-11</slug>
      <description><![CDATA[Category 12 description]]></description>
      <count>1</count>
      <link>http://your-wordpress-site.com/category-base/cat-1/cat-12/</link>
    </resource>
  </resources>
  <items>
    <item key="0">
      <ID>5</ID>
      <link>http://your-wordpress-site.com/cat-1/post-1/</link>
      <slug>post-1</slug>
      <title><![CDATA[Post 1]]></title>
      <excerpt><![CDATA[Post 1 excerpt]]></excerpt>
    </item>
  </items>
</root>
```

Response example for request at `http://your-wordpress-site.com/cat-1/post-1/?format=xml`

```xml
<root>
  <items>
    <item key="0">
      <ID>5</ID>
      <link>http://your-wordpress-site.com/cat-1/post-1/</link>
      <slug>post-1</slug>
      <title><![CDATA[Post 1]]></title>
      <author><![CDATA[Xmas]]></author>
      <date>2014-03-05T00:00:00+00:00</date>
      <tags><![CDATA[Tag 1, Tag 2, Tag 3]]></tags>
      <categories><![CDATA[Category 1, Category 11, Category 12]]></categories>
      <excerpt><![CDATA[Post 1 excerpt]]></excerpt>
      <content><![CDATA[Post 1 content]]></content>
      <html-content><![CDATA[<p>Post 1 content</p> ]]></html-content>
      <custom-fields>
        <custom-field key="0">
          <key><![CDATA[Custom 1]]></key>
          <values>
            <value key="0"><![CDATA[Value 1]]></value>
            <value key="1"><![CDATA[Value 11]]></value>
          </values>
        </custom-field>
        <custom-field key="1">
          <key><![CDATA[Custom 2]]></key>
          <values>
            <value key="0"><![CDATA[Value 2]]></value>
          </values>
        </custom-field>
      </custom-fields>
      <attachments>
        <attachment key="0">
          <ID>13</ID>
          <title><![CDATA[Attachment 1]]></title>
          <caption><![CDATA[Attachment 1 caption]]></caption>
          <description><![CDATA[Attachment 1 description]]></description>
          <html-description><![CDATA[<p>Attachment 1 description</p> ]]></html-description>
          <mime-type>application/pdf</mime-type>
          <link>http://your-wordpress-site.com/wp-content/uploads/2014/03/attachment-1.pdf</link>
        </attachment>
        <attachment key="1">
          <ID>15</ID>
          <title><![CDATA[Attachment 2]]></title>
          <caption><![CDATA[Attachment 2 caption]]></caption>
          <description><![CDATA[Attachment 2 description]]></description>
          <html-description><![CDATA[<p>Attachment 2 description</p> ]]></html-description>
          <mime-type>image/jpeg</mime-type>
          <link>http://your-wordpress-site.com/wp-content/uploads/2014/03/attachment-2.jpg</link>
          <width>640</width>
          <height>482</height>
          <sizes>
            <thumbnail>
              <width>150</width>
              <height>150</height>
              <mime-type>image/jpeg</mime-type>
              <link>http://your-wordpress-site.com/wp-content/uploads/2014/03/attachment-2-150x150.jpg</link>
            </thumbnail>
            <medium>
              <width>300</width>
              <height>225</height>
              <mime-type>image/jpeg</mime-type>
              <link>http://your-wordpress-site.com/wp-content/uploads/2014/03/attachment-2-300x225.jpg</link>
            </medium>
          </sizes>
        </attachment>
      </attachments>
      <comments>
        <comment key="0">
          <ID>2</ID>
          <author><![CDATA[Xmas]]></author>
          <author-email>jintetsu@gmail.com</author-email>
          <date>2014-03-05T00:00:00+00:00</date>
          <content><![CDATA[Comment 1 on post 1]]></content>
          <html-content><![CDATA[<p>Comment 1 on post 1</p> ]]></html-content>
        </comment>
        <comment key="1">
          <ID>3</ID>
          <author><![CDATA[Xmas]]></author>
          <author-email>jintetsu@gmail.com</author-email>
          <date>2014-03-05T00:00:00+00:00</date>
          <content><![CDATA[Comment 2 on post 1]]></content>
          <html-content><![CDATA[<p>Comment 2 on post 1</p> ]]></html-content>
        </comment>
      </comments>
    </item>
  </items>
</root>
```

API Paths
-

While the theme is active requests with path that start with the category base, continue with a valid sequence of resources and end with a valid item will correctly return that item.

Take for example the resources tree

`cat-1 --> cat-11 --> cat-111`

If an item `post-1` is assigned to `cat-11` and `cat-111` resources then

* `http://your-wordpress-site.com/cat-1/cat-11/post-1/ (WordPress default)`
* `http://your-wordpress-site.com/category-base/cat-1/cat-11/post-1/`
* `http://your-wordpress-site.com/category-base/cat-1/cat-11/cat-111/post-1/`

will all be considered valid `post-1` urls.

Customization
-

The API HTML visual representation only purpose is just to give and idea of the site resources tree and data. Feel free to substitute it with your own HTML, building your theme on top of API Press while keeping the API logic intact.

Bonus: Full items
-

While asking for resources the eventual items listed will present only a subset of their data, which you can retrieve with a request to the items themselves. If you add the `fullitems` param equal to 1 or true to your resource request url the items in the response will have all their data.

Example: `http://your-wordpress-site.com/category-base/cat-1/cat-11/?format=json&fullitems=true`

Bonus: Pages
-

API Press theme is already configured to return pages data in the requested format, just remember that pages remains outside the _categories-as-resources_ paradigm, so they won't be shown under the `http://your-wordpress-site.com/category-base/` entry point.

Also if you set your category base equals to a page slug, while the API logic will continue to work, `http://your-wordpress-site.com/category-base/` will show your page instead of the resources list.

License
-

_GNU General Public License v2 or later_
