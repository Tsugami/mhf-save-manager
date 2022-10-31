<style>
.carousel-indicators>li {
  background-color: #999;
}

.item-col {
  height: 79px;
  width: 117px;
}
</style>

<nav>
  <div class="nav nav-tabs" id="itembox-preview" role="tablist">
    <a class="nav-link active" id="nav-carousel-tab" data-toggle="tab" role="tab" aria-controls="nav-carousel"
      aria-selected="true">Carousel</a>
    <a class="nav-link" id="nav-table-tab" data-toggle="tab" role="tab" aria-controls="nav-table"
      aria-selected="false">Table</a>
  </div>
</nav>

<div class="row">
  <div class="tab-content" id="itembox-previewContent">
    <div class="tab-pane fade show active" id="nav-carousel" role="tabpanel" aria-labelledby="nav-carousel-tab">
      ...df
    </div>
    <div class="tab-pane fade" id="nav-table" role="tabpanel" aria-labelledby="nav-table-tab">
      ...df2
    </div>

  </div>
</div>


<div class="tab-content" id="itembox-previewContent">
  <div class="tab-pane fade show active" id="carousel-tab-pane" role="tabpanel" aria-labelledby="carousel-tab"
    tabindex="0">
    ...df
  </div>
  <div class="tab-pane fade show active" id="2-tab-pane" role="tabpanel" aria-labelledby="2-tab" tabindex="0">
    ...df
  </div>
  <div class="tab-pane fade" id="table-tab-pane" role="tabpanel" aria-labelledby="table-tab" tabindex="1">
    ...2
    <div class="mt-4 mx-3">
      <table class="table table-striped item-table ">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col"><?php echo $UILocale['ID']?></th>
            <th scope="col" style="width: 25%"><?php echo $UILocale['Item']?></th>
            <th scope="col"><?php echo $UILocale['Quantity']?></th>
            <th scope="col"><?php echo $UILocale['Type']?></th>
            <th><?php echo $UILocale['Edit']?></th>
          </tr>
        </thead>
        <tbody>
          <?php
            $itemCount = 0;
            foreach ($itembox as $item) {
              $tmpItem = \MHFSaveManager\Service\ItemsService::getForLocale()[$item->getId()];
                printf('
                  <tr>
                    <td scope="row">%s</td>
                    <td>
                      <img class="item-icon" src="/img/item/%s%s.png">
                      <span style="font-size: 12px;">%s</span>
                    </td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td><i class="fas fa-user-edit item-col" data-id="%s" data-quantity="%s" data-slot="%s" role="button"></i></td>
                  </tr>',
                  ++$itemCount,
                  $tmpItem['icon'],
                  $tmpItem['color'],
                  implode(' ', preg_split('/(?=[A-Z][^A-Z][^A-Z])/', $item->getName())),
                  $item->getQuantity(),
                  $tmpItem['icon'],        
                  $item->getId(),
                  $item->getId(),
                  $item->getQuantity(),
                  $item->getSlot(),
                );
              }
              ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div id="itemboxSlotEdit" class="modal fade" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="itemboxSlotEditTitle"><?php echo $UILocale['Editing Itemslot']?>: <b></b></h5>
      </div>
      <div class="modal-body">
        <h6><?php echo $UILocale['Item']?>:</h6>
        <div class="input-group mb-2">
          <select class="form-control" id="itemboxSlotItem">
            <?php
                        foreach (\MHFSaveManager\Service\ItemsService::getForLocale() as $id => $item) {
                            printf('<option data-icon="%s" data-color="%s" value="%s">%s</option>', $item['icon'], $item['color'], $id, $item['name']);
                        }
                        ?>
          </select>
        </div>

        <h6><?php echo $UILocale['Quantity']?>:</h6>
        <div class="input-group mb-2">
          <input type="number" class="form-control" id="itemboxSlotQuantity" placeholder="999" min="1" max="999">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $UILocale['Close']?></button>
        <button type="button" class="btn btn-primary" id="itemboxSlotSave"><?php echo $UILocale['Save']?></button>
      </div>
    </div>
  </div>
</div>


<div id="itemboxPagination" class="carousel slide" data-interval="false" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#itemboxPagination" data-slide-to="0" class="active"></li>
    <?php
        for ($i = 0; $i < count($itembox) / 100 -1; $i++) {
            printf('<li data-target="#itemboxPagination" data-slide-to="%s"></li>', $i+1);
        }
        ?>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <?php
            $itemCount = 0;
            $rowCount = 0;
            $pageCount = 0;
            foreach ($itembox as $item) {
                if ($itemCount == 0) {
                    echo '<div class="row item-row">';
                }
                $tmpItem = \MHFSaveManager\Service\ItemsService::getForLocale()[$item->getId()];
                printf('
                            <div class="col item-col" data-id="%s" data-quantity="%s" data-slot="%s">
                                <img class="item-icon" src="/img/item/%s%s.png">
                                <span style="font-size: 12px;"><b>[x%s]</b><br>%s</span>
                            </div>',
                    $item->getId(),
                    $item->getQuantity(),
                    $item->getSlot(),
                    $tmpItem['icon'],
                    $tmpItem['color'],
                    $item->getQuantity(),
                    implode(' ',preg_split('/(?=[A-Z][^A-Z][^A-Z])/', $item->getName()))
                );
                if (++$itemCount >= 10) {
                    echo '</div>';
                    $itemCount = 0;
                    $rowCount++;
                }
                if ($rowCount >= 10) {
                    echo '</div>';
                    $rowCount = 0;
                    $itemCount = 0;
                    $pageCount++;
                    if (count($itembox) > $pageCount*100) {
                        echo '<div class="carousel-item">';
                    }
                }
            }
            ?>
    </div>
    <a class="carousel-control-prev" href="#itemboxPagination" role="button"
      style="width: auto; background-color: black; height: 15%; margin-top: 25%;" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#itemboxPagination" role="button"
      style="width: auto; background-color: black; height: 15%; margin-top: 25%;" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
    <br>
    <h3 id="slidetext" style="  top: -25px;position: relative;"></h3>
  </div>


  <script>
  $(document).ready(function() {
    $('.item-table').DataTable({
      language: {
        <?php
          if ($_SESSION['locale'] == 'ja_JP') {
            echo "url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/ja.json'";
          }
        ?>
      }
    });
  });
  </script>