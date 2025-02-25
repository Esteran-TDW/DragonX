<?php
// Empêche l'accès direct au fichier
if (!defined('DIR_CLASS_SYS')) {
    die('Accès direct interdit');
}
?>
<footer>
    <div class="footer-container">
        <p>&copy; <?php echo date('Y'); ?> Votre Nom ou Organisation. Tous droits réservés.</p>
        <nav>
            <ul>
                <li><a href="terms.php">Conditions d'utilisation</a></li>
                <li><a href="privacy.php">Politique de confidentialité</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </div>
    <!-- JavaScript files -->
    <script src="<?php echo JS; ?>/main.js"></script>
</footer>
</body>

</html>