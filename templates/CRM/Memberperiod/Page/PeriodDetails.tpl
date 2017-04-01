{if $data}
  {include file="CRM/common/jsortable.tpl"}
  <div class="view-content view-contact-groups">
    <div class="ht-one"></div>
      <h3>{ts}There are {$data|@count} Membership(s) associated with this contact{/ts}</h3>
	{foreach from=$data key=membershipId item=membership}   
            <table id="current_group" class="display">
              <thead>
                <tr>
                  <th>{ts}Membership ID {/ts}</th>
                  <th>{ts}Term/Period {/ts}</th>
                  <th>{ts}Period Start Date{/ts}</th>
                  <th>{ts}Period End Date{/ts}</th>
                  <th>{ts}Payment Amount{/ts}</th>
                </tr>
              </thead>
            {foreach from=$membership key=count item=period}
	  <tr id="group_contact-{$period.id}" class="crm-entity {cycle values="odd-row,even-row"}">
            {assign var=contactId value=$period.contact_id}
            <td class="bold">
		{capture assign=crmURL}{crmURL p='civicrm/contact/view/membership' q="reset=1&id=$membershipId&cid=$contactId&action=view&context=membership&selectedChild=member"}{/capture}
		<a href="{$crmURL}" target="_blank">
		   {$membershipId}
		</a>
	    
	    </td>
            <td>{$count}</td>
	    <td>{$period.start_date|crmDate}</td>
	    <td>{$period.end_date|crmDate}</td>
            <td>
            {assign var=contributionId value=$period.contribution_id}
		{capture assign=crmURL}{crmURL p='civicrm/contact/view/contribution' q="reset=1&id=$contributionId&cid=$contactId&action=view&context=membership&selectedChild=contribute"}{/capture}
		<a href="{$crmURL}" target="_blank">
		  {$period.contribution_amount|crmMoney}
		</a>
          </td>
	   
	  </tr>
                {/foreach}
                </table>
                <br/>
	{/foreach}
  </div>
{/if}