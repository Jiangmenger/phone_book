<?php
namespace app\commands;

use yii\console\Controller;

class PhonebookController extends Controller
{
  // 读取和保存文件
    private function loadContacts()
    {
    $file = '/opt/phonebook/data/contract.json';
    if (!file_exists($file)) {
        return [];
    }

    return json_decode(file_get_contents($file), true);
    }

    private function saveContacts($contacts)
    {
    file_put_contents('/opt/phonebook/data/contract.json', json_encode($contacts, JSON_PRETTY_PRINT));
    }


    //增添联系人 (actionAdd)
    public function actionAdd($name, $phone)
    {
    $contacts = $this->loadContacts();
    $contacts[] = ['id' => count($contacts) + 1, 'name' => $name, 'phone' => $phone];
    $this->saveContacts($contacts);

    echo "Contact added successfully.\n";
    }
    //搜索联系人 (actionSearch)
    public function actionSearch($name)
    {
    $contacts = $this->loadContacts();
    $found = array_filter($contacts, function ($contact) use ($name) {
        return strpos(strtolower($contact['name']), strtolower($name)) !== false;
    });

    if (empty($found)) {
        echo "No contacts found.\n";
        return;
    }

    foreach ($found as $contact) {
        echo "ID: {$contact['id']}, Name: {$contact['name']}, Phone: {$contact['phone']}\n";
    }
    }

    //删除联系人 (actionDelete)
    public function actionDelete($id)
    {
    $contacts = $this->loadContacts();
    foreach ($contacts as $i => $contact) {
        if ($contact['id'] == $id) {
        array_splice($contacts, $i, 1);
        $this->saveContacts($contacts);
        echo "Contact deleted successfully.\n";
        return;
        }
    }

    echo "Contact not found.\n";
    }
    //修改联系人 (actionUpdate)
    public function actionUpdate($id, $name, $phone)
    {
    $contacts = $this->loadContacts();
    foreach ($contacts as &$contact) {
        if ($contact['id'] == $id) {
        $contact['name'] = $name;
        $contact['phone'] = $phone;
        $this->saveContacts($contacts);
        echo "Contact updated successfully.\n";
        return;
        }
    }

    echo "Contact not found.\n";
    }

}





?>