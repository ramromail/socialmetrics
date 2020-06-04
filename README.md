# socialmetrics
A PHP application for calculating some metrics

## Tasks
### Average character length of a post / month
Average character length of a post has been calculated by counting the total number of characters in posts per month divided by total number of post per month.

The results are displayed as a key : value pair, where key denotes month and value denotes average number of characters.

### Longest post by character length / month
Longest post by character length for each month has been calculated by counting the number of characters in each post.

The result are available under the key "Longest post by length per month". It contains month and then the length of the longest post in the month and the post itself.

### Total posts split by week
Total number posts per week has been calculated by, using the "created_time" field and converting it to equivalant week number.

The results are displayed under the key "Total ammount of posts by week", where each child key represents week number and the value represent total number of posts in that week.

### Average number of posts per user / month
Average number of posts per user per month has been calculated by counting the total number of posts throught the time period by each user and then dividing it by number of months during that period.

The results are displayed under the key "Average number of posts per user per month", it contains arrays which contains user id, user name and average number of posts per month by that user.