<?php if(Helper::checkIfUserLoggedIn()): ?>
<div class="container">
<div class="row">
<div class="col-md-12">
    <h2 class="text-center mt-3 font-weight-bold">Nakupovalni seznam</h2>
<div id="clipboard-wrapper">
  <section id="clipboard">
    <div id="clipboard-grip">
    </div>
    <div id="clipboard-lever">
    </div>
    <div class="list">
     <input id="list" type="text" placeholder="dodaj na seznam">
        <button onclick="addToList()" type="button" class="btn-primary">Dodaj</button>
</div>
    <ul class="list">
        <?php foreach($Lists as $List): ?>
     <li class="listitem <?= ($List->Completed == "1") ? "completed" : ""; ?>">
        <input <?= ($List->Completed == "1") ? "checked" : ""; ?> onchange="markCompleted('<?= $List->ID; ?>')" id="checkbox-input-<?= $List->ID; ?>" class="checkbox-input" type="checkbox">
        <input onchange="editList('<?= $List->ID; ?>')" id="<?= $List->ID; ?>" value="<?= $List->ListText; ?>" type="text" placeholder="dodaj na seznam">
        <button onclick="deleteList('<?= $List->ID; ?>')" class="btn-secondary" type="button">Izbriši</button>
      </li>
       <?php endforeach; ?>
    </ul>
  </section>
</div>
    <div>

</div>
</div>
<?php endif; ?>