<footer class="footer">=
    <div class="footer__copyright">
        <p>Copyright © <?= date("Y") . " " . get_bloginfo("title") ?>. All rights reserved</p>
        <p>Website by: <a href="https://emmbrand.com">emmbrand</a></p>
    </div>
</footer>
<?php wp_footer(); ?>

<?php if (is_page_template("contact.php")) { ?>
    <script>
        sendMail(
            '<?php echo RECAPTCHA_SITE_KEY ?>',
            '<?php echo admin_url('admin-ajax.php'); ?>',
            ["name", "email"]
        )
    </script>
<?php } ?>
</body>
</html>
