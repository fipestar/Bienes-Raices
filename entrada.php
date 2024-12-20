<?php 
   require 'includes/funciones.php';
   incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Guia para la decoracion de tu hogar</h1>

        

        <picture>
            <source srcset="build/img/destacada2.webp" type="image/webp">
            <source srcset="build/img/destacada2.jpg" type="image/jpeg">
            <img loading="lazy" src="build/img/destacada2.jpg" alt="imagen de la propiedad">
        </picture>

        <p class="informacion-meta">Escrito el <span>04/05/2025</span> Por <span>Admin</span></p>
        <div class="resumen-propiedad">
            <p>Praesent tempus arcu ante, vitae sodales lectus porttitor ac. Suspendisse tincidunt nulla venenatis porta tempus. Ut sem nulla, pulvinar vel magna bibendum, porttitor scelerisque felis. Praesent purus nulla, commodo eu mollis in, tristique in arcu. Nam lobortis neque ut facilisis feugiat. Ut tempor luctus lorem et rutrum. Phasellus lacinia quis orci sed tincidunt. Cras eu ligula nec purus volutpat vestibulum tincidunt ut augue. Nulla eu viverra mauris, a condimentum sapien. Integer tincidunt dolor ac dolor pharetra, sit amet venenatis tortor venenatis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla viverra placerat mauris, quis dapibus ante malesuada ac.</p>
            
            <p>Vestibulum sit amet laoreet augue, in consequat nisi. Cras commodo varius justo, id aliquet neque condimentum ac. Etiam eget blandit nibh. Duis malesuada libero at velit ultricies lacinia. Donec at bibendum ante. Donec commodo, est a euismod vehicula, leo quam efficitur lacus, sed tempor mi neque ac lacus. Maecenas mollis sit amet metus sit amet facilisis.</p>
        </div>
    </main>

    <?php 
   incluirTemplate('footer'); 
?>