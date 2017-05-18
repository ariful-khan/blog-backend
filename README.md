blog-backend
============
run the application using 

```
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load
bin/console server:run --port=8000
```

the end point for blog post are
 ```
http://localhost:8000/api/posts
```

now run the front end application so the the blog post.
