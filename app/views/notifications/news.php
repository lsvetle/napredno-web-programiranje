<?php require APPROOT . '/views/includes/headCalendar.php'; ?>

<div class="navbar-dark">
    <?php require APPROOT . '/views/includes/navigation.php'; ?>
</div>

<body>
    <div class="section-landing">
        <nav class="notificationHead">
            <ul></ul>
        </nav>

        <div class="container-notifications">
            <?php if (empty($data['news'])): ?>
                <div class="noNotification">
                    <h3 class="noNotification">
                        There is no news.
                    </h3>
                </div>
            <?php else: ?>
                <!-- Section to display retrieved news articles -->
                <div class="container-item">
                    <?php foreach ($data['news'] as $index => $article): ?>
                        <div class="article">
                            <h3 style="font-size: 1.2em;">
                                <strong>
                                    <a href="<?php echo $article->url; ?>" class="article-link">
                                        <?php echo $article->title; ?>
                                    </a>
                                </strong>
                            </h3>
                            <p class="article-description"><?php echo $article->description; ?></p>
                            <p class="article-meta">
                                <span>-➤ Published at: <?php echo date('M d, Y', strtotime($article->publishedAt)) . ', ' . date('h:i A', strtotime($article->publishedAt)); ?></span>
                                <span>-➤ <a href="<?php echo $article->url; ?>">Read more</a></span>
                            </p>
                            <?php if ($index < count($data['news']) - 1): ?>
                                <hr class="article-divider">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>&copy; Copyright 2024 Luka Svetlečić</p>
        </div>
    </div>

    
    <style>
        .container-item {
            padding: 20px;
        }

        .article {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }

        .article-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }

        .article-title:hover {
            color: #007bff;
        }

        .article-link {
            text-decoration: none;
            color: #333;
        }

        .article-description {
            margin-bottom: 10px;
        }

        .article-meta {
            font-size: 14px;
            color: #666;
        }

        .article-divider {
            margin-top: 20px;
            margin-bottom: 20px;
            border: none;
        }

        .noNotification {
            padding: 20px;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }
    </style>
</body>
