<?php
include  'header.tpl.php';

?>
    <main>
        <div class="container">
            <div class="my-auto">
                <div class="card">

                    <form class="form" method="POST" action="<?=BASE;?>task/add">
                        <div class="card-header">
                            <h3>Rellena los datos de la nota...</h3>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="description" placeholder="descripcion" required>
                        </div>

                        <div class="form-group">
                            <label for="due_date">Fecha y tiempo limite:</label>
                            <input type="date" id="due_date" class="form-control" name="due_date" value="<?= date("Y-m-d");?>">
                        </div>

                        <div class="form-group">

                            <button class="btn btn-primary" type="submit">Añadir</button>
                        </div>

                        </legend>
                    </form>
                </div>
            </div>
        </div>
    </main>

<?php

include 'footer.tpl.php';