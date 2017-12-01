<?php
    // $province_and_cities = get_all_prov_and_cities($mysqli);
    $categories_and_subcategories = get_categories_and_subcategories($mysqli);
    $provinces_and_cities = get_provinces_and_cities($mysqli);
    $types = get_different_ad_types($mysqli);
?>

<style>
    .form-inline > * {
    margin:5px 3px;
    }
</style>

<script>
    let categories = <?= json_encode($categories_and_subcategories) ?>;
    let provinces = <?= json_encode($provinces_and_cities) ?>;

    window.onload = () => {
        let category = "<?= $category ?>";
        let subcategory = "<?= $subcategory ?>";
        let categoryDD = document.getElementById("category");
        let subcategoryDD = document.getElementById("subcategory");
        setOption(categoryDD, category);
        updateDropdown(categoryDD, categories, 'subcategory', 'Subcategory...');
        setOption(subcategoryDD, subcategory);

        let province = "<?= $province ?>";
        let city = "<?= $city ?>";
        let provinceDD = document.getElementById("province");
        let cityDD = document.getElementById("city");
        setOption(provinceDD, province);
        updateDropdown(provinceDD, provinces, 'city', 'City...');
        setOption(cityDD, city);

        let typeDD = document.getElementById("type")
        setOption(typeDD, "<?=$type?>");
    };
   
    function setOption(selectElement, value) {
        var options = selectElement.options;
        for (var i = 0, optionsLength = options.length; i < optionsLength; i++) {
            if (options[i].value == value) {
                selectElement.selectedIndex = i;
                return true;
            }
        }
        return false;
    }


    function updateDropdown(dropdown, values, id, defaultValue){
        let subvalues = dropdown.value ? values[dropdown.value]: [];
        let dropdownToPopulate = document.getElementById(id);
        while (dropdownToPopulate.options.length > 0) {
            dropdownToPopulate.remove(0);
        }

        let option = document.createElement("option");
        option.text = defaultValue;
        dropdownToPopulate.add(option);

        subvalues.forEach((sub) => {
            var option = document.createElement("option");
            option.value = sub
            option.text = sub;
            dropdownToPopulate.add(option);
        })
    }
</script>

<form class="form-inline" method="get">
  <div class="form-group">
    <label for="category">Category</label>
    <select id="category" class="form-control" name="category" onchange="updateDropdown(this, categories, 'subcategory', 'Subcategory...');">
            <option value="">Category...</option>
            <?php foreach($categories_and_subcategories as $category => $subcategories) { ?>
                <option value="<?= $category ?>"><?= $category ?></option>
            <?php } ?>
    </select>
    <select id="subcategory" class="form-control" name="subcategory">
            <option value="">Subcategory...</option>
    </select>
  </div>
  <div class="form-group">
    <label for="province">Province</label>
    <select id="province" class="form-control" name="province" onchange="updateDropdown(this, provinces, 'city', 'City...');">
            <option value="">Province..</option>
            <?php foreach($provinces_and_cities as $province => $city) { ?>
                <option value="<?= $province ?>"><?= $province ?></option>
            <?php } ?>
    </select>
    <select id="city" class="form-control" name="city">
            <option value="">City...</option>
    </select>
  </div>

  <div class="form-group">
    <label for="type">Type</label>
    <select id="type" class="form-control" name="type">
            <option value="">Type..</option>
            <?php foreach($types as $type) { ?>
                <?php var_dump($type) ?>
                <option value="<?= $type["type"] ?>"><?= $type["type"] ?></option>
            <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label for="seller">Seller</label>
    <input type="text" class="form-control" id="seller" name="seller" placeholder="seller" value="<?=$seller?>">
  </div>
  <button type="submit" class="btn btn-primary">Search</button>
</form>