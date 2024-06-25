
<?php
require '../server/mysqli_connector.php';
$limitator = 20;
$sql = "SELECT * FROM manga WHERE ID = $_GET[manga]";
$result = mysqli_query($conn, $sql);
$mangas = array(); 
$mangaviews = "SELECT * FROM mangaviews WHERE manga_ID = $_GET[manga]";
$resultviews1 = $conn->query($mangaviews);
$rowviews = $resultviews1->fetch_assoc();
$row_cnt = mysqli_num_rows($result);

if($row_cnt > 0 || $row_cnt != NULL){
?>
    <h1><?php if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo $row['title'];
        ?></h1>
        <div>
        <div class="container mt-2">
        <div class="row">
        <div class="col-sm-5">
            <div class="caratula"><img src="../mangas/<?php echo $row['ID'] ?>/caratula.png" class="float-start d-block img-thumbnail mangacover" alt="caratula"></div>   
        </div>
            <div class="col-sm-7">
            <div class="">
               <aside class="nones">
                
                    <h2>Descripción</h2>
                    <p><?php echo   $row['description']; ?> </p>
                    <p>Visitas: <?php echo $rowviews["views_count"] ?> </p>
                    <p>Añadido en: <?php echo $row['uploadDate']; ?></p>
                    <p>Ultima actualizacion: <?php echo $row['lastUpdate'];   
                    }
                      } ?></p>
               </aside>
            </div>
        </div>
        </div>
        </div>
        </div>
       
        <div class="container mt-2 d-inline-flex etiqueta">
            <?php
            $sql = "SELECT * FROM mangagenders INNER JOIN mangagenders_manga ON mangagenders.g_ID = mangagenders_manga.MangaGenders_ID INNER JOIN manga ON mangagenders_manga.Manga_ID = manga.ID WHERE manga.ID =$_GET[manga];";
            $result = mysqli_query($conn, $sql);
            $categorias = array();
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $categorias[] = $row;
                }
            }
            foreach ($categorias as $categorias) { ?>

                <a href="../controllers/categorias.php?categoria=<?php echo $categorias['Name']; ?>" type="button" class="btn btn-outline-secondary separado"><i class="bi bi-tag"></i><?php echo $categorias['Name']; ?></a><?php } ?>
            <!-- Pregunto si inició sesion-->

            <?php if (!isset($_SESSION['datos'])) { ?>
                <button type="button" class="btn btn-secondary favbtn" onclick="location.href='login.php'"><i class="bi bi-star"></i></button>
                <button type="button" class="btn btn-secondary favbtn" onclick="location.href='login.php'"><i class="bi bi-heart"></i></button>

                <!-- No le dio like-->
                <?php } else {
                if (mysqli_num_rows($resultVerify) == 0) { ?>
                    <button type="button" class="btn btn-secondary favbtn" onclick="location.href='manga/like.php?manga=<?php echo $manga_id; ?>'"><i class="bi bi-heart"></i></button>
                    <!-- Le dio like-->
                <?php } else { ?>
                    <button type="button" class="btn btn-secondary favbtn" onclick="location.href='manga/like.php?manga=<?php echo $manga_id; ?>'"><i class="bi bi-heart-fill"></i></button>
                    <!-- No le dio fav-->
                <?php }
                if (mysqli_num_rows($resultVerifyFav) == 0) { ?>
                    <button type="button" class="btn btn-secondary favbtn" onclick="location.href='manga/favourite.php?manga=<?php echo $manga_id; ?>'"><i class="bi bi-star"></i></button>
                    <!-- Hay que hacer que muestre un mensaje de error cuando el usuario le da favorito a un manga cuando ya tiene uno en favoritos -->
                    <div class="bg-danger">
                        <?php echo (isset($msj) ? $msj : ''); ?>
                    </div>
                    <!-- Le dio fav -->
                <?php } else { ?>
                    <button type="button" class="btn btn-secondary favbtn" onclick="location.href='manga/favourite.php?manga=<?php echo $manga_id; ?>'"><i class="bi bi-star-fill"></i></button>
            <?php }
            } ?>
        </div>

        <hr class="invisible">

        <div>
            <div class="container capitulos" id="chapters">
            <h2 class="">Capitulos</h2>
            <hr>
                <?php
                $sql = "SELECT * FROM manga INNER JOIN mangachapters ON manga.ID = mangachapters.Manga_ID WHERE manga.ID = $_GET[manga] ORDER BY mangachapters.number DESC;";
                $result = mysqli_query($conn, $sql);
                $capitulos = array();
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $capitulos[] = $row;
                    }
                }
                foreach ($capitulos as $capitulos) { ?>

                    <article onclick="location.href='intlectura1.php?manga=<?php echo $_GET['manga']; ?>&capitulo=<?php echo $capitulos['number']; ?>';" style="cursor: pointer">
                        <a href="intlectura1.php?manga=<?php echo $_GET['manga']; ?>&capitulo=<?php echo $capitulos['number']; ?>"> Capitulo <?php echo $capitulos['number']; ?></a>
                    </article>
                <?php }
                if (!mysqli_num_rows($result)) {
                    echo "<p class='text'>No hay capitulos disponibles</p>";
                }
                ?>
            </div>
        </div>
    </div>
            </div>
<?php }
else { ?>
Manga no encontrado
<?php } ?>
<hr class="invisible">
