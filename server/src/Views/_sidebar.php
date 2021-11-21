<ul>
    <li><a href="">Liked Songs</a></li>
    <li><a href="">Liked Albums</a></li>
    <li><a href="">Liked Artists</a></li>
    <?php if(isset($_SESSION['user']['adm'])): ?>
        <li><a href="">Songs Menu</a></li>
        <li><a href="">Albums Menu</a></li>
        <li><a href="">Artists Menu</a></li>
    <?php endif; ?>
</ul>