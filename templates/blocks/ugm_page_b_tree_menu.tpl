<link rel="stylesheet" href="<{xoAppUrl}>modules/tadtools/dtree/dtree.css" type="text/css">
<script src="<{xoAppUrl}>modules/tadtools/dtree/dtree.js" type="text/javascript"></script>
<script type="text/javascript"> 
  d = new dTree('d','<{xoAppUrl modules/tadtools/dtree}>');//new一个树对象 
  //设置树的节点及其相关属性 
  d.add(0,-1,'<{$block.title}>');
  
  <{foreach from=$block.rows item=rows}>    
    d.add(<{$rows.menu_sn}>,<{$rows.menu_ofsn}>,"<{$rows.menu_title}>","<{$rows.menu_url}>","","<{if $rows.menu_new}>_blank<{/if}>");
    <{if $rows.sub}>
      <{foreach from=$rows.sub item=sub1}>
        d.add(<{$sub1.menu_sn}>,<{$sub1.menu_ofsn}>,"<{$sub1.menu_title}>","<{$sub1.menu_url}>","","<{if $sub1.menu_new}>_blank<{/if}>");
        <{foreach from=$sub1.sub item=sub2}>
          d.add(<{$sub2.menu_sn}>,<{$sub2.menu_ofsn}>,"<{$sub2.menu_title}>","<{$sub2.menu_url}>","","<{if $sub2.menu_new}>_blank<{/if}>");
        <{/foreach}> 
      <{/foreach}> 
    <{/if}>
  <{/foreach}> 
  //config配置，设置文件夹不能被链接，即有子节点的不能被链接。 
  
  d.config.folderLinks=true; 
  document.write(d); 
</script> 
<p>
  <a href="javascript: d.openAll();">展開</a> | <a href="javascript:d.closeAll();">闔起</a>
</p>