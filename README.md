# EC01

This package attempts to pull together the files and directory structure needed to run a community of between 150 and 450 people. It is not intended for a community or set of people numbering 5,000 and up. By using the directory structure and static files as much as possible before a database is invoked, this makes this configuration readable by a lower level of technology. That is, anything that can view an image or a view and edit a text file can use this system, without the need for a database set up on the system. This is by design.

## Configuration

A lot of the "heavy lifting" is done by the configuration files (`/c/config/`). The intent here is to move toward a platform agnostic approach, so that the directory structure and constants already set and worked upon can be used by different platforms. It is recognized that each platform may have its own particular flavour, however, as the end results MUST be valid HTML, CSS and Javascript, the abstract model that one uses SHOULD be able to be used by different underlying approaches. Some frameworks may use JSON or YAML to store their configurations. However, with a clear understanding of the abstract model being used and an understanding of the particulars of each framework, it should be possible to translate the configuration set up here to these other types of files.

## Security

One of the surprises (and it was a surprise) was that--if ALL of the PHP files were placed in a directory apart from ALL static text based files, CSS, Javascript, media and the like--then it would be possible to secure these PHP files with htaccess level password protection. That is, instead of allowing the outside world to ping server based PHP files without restriction, the added layer of htaccess password protection would prevent this from even happening! (Nobel prize, anyone?) As long as all of the static files (such as media, CSS, Javascript and so on), were freely available outside of this protected zone, then the site should continue to function as normal. Of course, there may be nuances, where certain PHP files need to be browser accessible, however, in general, it can be seen that the underlying technology should be invisible to the end user.

Although the framework the author is familiar with does not place all CSS and Javascript files in a directory separate from PHP files by default, enough is known about how to make this happen that it is not a technical concern. However, the current trend is to allow for CSS and JS files to reside right next to PHP files in the directory. What this approach does is simply to take the thinking that style should be separated from content one step further and then also ensure that style is separate from content which is then also all separate from the PHP files that stitch it all together. Although this is a relatively simple concept to understand, it is known that it won't happen automatically and there will be glitches, as most plugins and themes include CSS and JS files mixed among their PHP files by default.

## CDN Ready

To make this work, the steps that certain plugins take to bring all of the CSS, JS and media out of their respective directories and place them somewhere else so that they can be copied to a CDN is the step that will need to be taken here. However, instead of copying these files directly to a CDN, it will stop at an  intermediate step. That is, they will all be placed in a "CDN Ready" directory. This directory is being labelled `/0` and is in the root directory.

## Pricing

While everyone would love to have free, in the author's experience, free is expensive. This is due in part to the fact that something received for free costs the user nothing and therefore they are likely to pay little attention to it. On ther other hand, something that cost something is likely to be valued more, simply because of that cost.

Another part to the equation is that the person writing the code is living in an environment where the monetary economy is deeply engrained. Therefore, to even sit at a coffeeshop, pay for a small cup of coffee and use their wifi to program still has a cost. If there is no income anywhere (as everyone would like everything free), then even that small cup of coffeee can't be paid for. In other words, 0 + 0 = 0. There has to be some give somewhere.

The entire direction this community is going is to *de*emphasize money, not eliminate it entirely. That is, by making use of land to produce food, water and energy and to design the community so that its participants can support each other by intention, and by design, less money should be needed when interacting with each other and with others not directly part of the community.

In a personal example, the author received a reasonable hourly wage when working for his Dad on the farm. This wasn't absolutely required, as most of the author's needs were provided for, yet it gave enough of an incentive to pay attention and do the work well. There may have been some bitterness if that payment wasn't received, but it was, and there was a good feeling to the whole thing.

These wages earned could then be used for other things; like paying for a university eduction, buying clothes or whatnot. In the same way, money _is_ convenient when interacting with someone one barely knows and wanted to acknowledge them. It seems fair that either a  payment of money should be made or a trade of a goods or service, so that there can be an equality in the exchange.

## Try 'n Buy

Thus, this package (EC01) as it is available here, is being made avaiable on an open source licence, which means changes can be made, but with the added restriction that a payment is requested to cover the cost of development, and help with further development. That price is being set at $2,950, or about a month's worth of work. In part, this is to give value to this work, as there are many nuances in it that make a difference. It is set at this price to make one think first before proceeding. That is the intent. It could be thought of as a community-in-a-box.

If a community of 500 is valued at $40,000 per year, then the expected income of those 500 people would be $20,000,000. If this package enables that community to run better, more efficiently, and allows for a net positive output, with a smaller total cost, then that $2,950 is a very good investment. Given that, then the intent also would be to kept the cost of this "community-in-a-box" consistent, even if further developments are added.

For an individual, parts of this package are in the process of being separated out from it, so that they can be used on their own. These range from single files going for $10, and on up. One of these packages will be a WordPress core bundled with software to be self installing, backed up, cached, secured and optimized.  This integrated WordPress bundle was the seed for this endeavour here and its price is being set at $295.

## Knowledge Base

In addition, work has been in progress for quite some time to pull together the essential documents, information and data needed to run a community. These need to be available online. Since the ability to search is so ubiquitous, being without this ability if one is offline makes it appear as if one has returned to the dark ages. However, there are no restrictions to placing content licensed as Creative Commons on a SSD drive or even a micro SD drive. A 32 GB micro SD drive or USB stick can hold an awful lot of data. But this data needs to be organized and filtered so that it contains only what is needed, and not what is not. This is a lot of work.

Thus, this "community-in-a-box" can also include this knowledge set. To date, this information is including key reference information, some of which has a high level of detail. For example, how to set up a laptop running the Ubuntu OS with a local server. This highly technical reference material typically can't be memorized and needs to be recorded somewhere in a static location so that it can be found again if needed. This is something that is taken for granted if one is online, but is sorely missed if one is not. If this information is NOT available, it may make it impossible to proceed. It may stop the community in its tracks. Thus this information is required and MUST be included.

## The Bootstrap Method

One of the nuances that is difficult to explain unless experienced is that it is possible to carefully store a small amount of information that can then be used to construct progressively more detailed and complex iterations. For example, taking the knowledge of how to install an WiFi attenna that can pick up a distant signal to have access to more information, one needs to know how to set up the initial configuration. This precise, technical information is not optional. If it is NOT available in an offline version, then that WiFi attenna can't be installed, and it WON'T work. Conversely, if the technical information IS available in an offline format, then the WiFi attenna CAN be installed, and it WILL work. The attenna could then be used to gain access to the virtually infinite amount of information that is currently available on the internet. (This is assuming we are in a remote area with no access otherwise.)
