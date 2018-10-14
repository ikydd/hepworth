<table class="table table-striped">
  <thead>
    <tr>
      <?php foreach($header as $head): ?>
      <th><?php echo $head; ?></th>
      <?php endforeach; ?>
    </tr>
  </thead>
  <tbody>
    <?php if(is_array($rows) && count($rows)): ?>
    <?php foreach($rows as $row): ?>
     <tr>
      <?php foreach($row as $prop): ?>
      <td><?php echo $prop; ?></td>
      <?php endforeach; ?>
     </tr>
    <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="100%"><?php echo $empty ? $empty : 'There are no rows in this table'; ?></td></tr>
    <?php endif; ?>
  </tbody>
</table>