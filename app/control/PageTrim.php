<?php

class PageTrim extends TPage
{
    public function onShow($param = null)
    {
        /*
        TTransaction::open('integrador');
        $itens = MiniMetaItem::where('cod_item','like','% %')->load();
        var_dump(count($itens));
        foreach($itens as $item){
            $item->cod_item = str_replace(' ','',$item->cod_item);
            $item->store();
        }
        
        TTransaction::close();
        */
    }
}
