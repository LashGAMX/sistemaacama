<select multiple="multiple" size="10" name="duallistbox_demo2" class="demo2">
    <option value="option1">Option 1</option>
    <option value="option2">Option 2</option>
    <option value="option3" selected="selected">Option 3</option>
    <option value="option4">Option 4</option>
    <option value="option5">Option 5</option>
    <option value="option6" selected="selected">Option 6</option>
    <option value="option7">Option 7</option>
    <option value="option8">Option 8</option>
    <option value="option9">Option 9</option>
    <option value="option0">Option 10</option>
    <option value="option0">Option 11</option>
    <option value="option0">Option 12</option>
  </select>
<!-- -->
  <script>
      $( document ).ready(function() {
        var demo2 = $('.demo2').bootstrapDualListbox({
      nonSelectedListLabel: 'Non-selected',
      selectedListLabel: 'Selected',
      preserveSelectionOnMove: 'moved',
      moveOnSelect: false,
      nonSelectedFilter: 'ion ([7-9]|[1][0-2])'
      });
    });

  </script>
