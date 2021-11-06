<?php
/*
 * This file is part of the NextDom software (https://github.com/NextDom or http://nextdom.github.io).
 * Copyright (c) 2018 NextDom.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$pluginName = init('m');
$plugin = plugin::byId($pluginName);
sendVarToJS('eqType', $plugin->getId());
$eqLogicList = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br/>
				<span>{{Configuration}}</span>
			</div>
			<div class="cursor eqLogicAction" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br/>
				<span>{{Ajouter}}</span>
			</div>
		</div>
		<legend><img style="width:40px" src="<?= $plugin->getPathImgIcon() ?>"/> {{Mes appareils}}</legend>
		<?php if (count($eqLogicList) == 0) { ?>
			<center>
				<span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n’avez pas encore d’appareil, cliquez sur configuration et cliquez sur synchroniser pour commencer}}</span>
			</center>
		<?php } else { ?>
			<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
			<div class="eqLogicThumbnailContainer">
				<?php
				foreach ($eqLogicList as $eqLogic) {
					$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard'; ?>
					<div class="eqLogicDisplayCard cursor <?= $opacity ?>" data-eqLogic_id="<?= $eqLogic->getId() ?>">
					<img src="<?= $eqLogic->getImage() ?>" />
					<br/>
					<span class="name"><?= $eqLogic->getHumanName(true, true) ?></span>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>

	<div class="col-xs-12 eqLogic" style="display: none;">
		<a class="btn btn-success eqLogicAction pull-right" data-action="save">
			<i class="fa fa-check-circle"></i> {{Sauvegarder}}
		</a>
		<a class="btn btn-danger eqLogicAction pull-right" data-action="remove">
			<i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
		<a class="btn btn-default eqLogicAction pull-right" data-action="configure">
			<i class="fa fa-cogs"></i> {{Configuration avancée}}
		</a>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation">
				<a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab"
				   data-action="returnToThumbnailDisplay">
					<i class="fa fa-arrow-circle-left"></i>
				</a>
			</li>
			<li role="presentation" class="active">
				<a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab">
					<i class="fas fa-tachometer-alt"></i> {{Equipement}}
				</a>
			</li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab">
					<i class="fa fa-list-alt"></i> {{Commande}}</a>
			</li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<div class="row">
					<div class="col-sm-3">
						<center>
							<img id="img_device" src="<?= $plugin->getPathImgIcon(); ?>" style="height : 200px;" />
							<div id="img_device_not_found">
								<p>{{Pas d’image de votre appareil ?}}</p>
								<p>{{Proposer une image}} <a href="https://github.com/lucguinchard/plugin-Mattermost/issues/new?assignees=&labels=type%3AEnhancement&template=LOGO_DEVICE_EMPTY.md&title=L%E2%80%99image+de+mon+appareil+%60aaa%60+n%E2%80%99existe+pas." target="_blank">{{ici}}</a>.</p>
							</div>
						</center>
					</div>
					<div class="col-sm-6">
						<form class="form-horizontal">
							<fieldset>
								<div class="form-group">
									<label class="col-sm-6 control-label" for="name">{{Nom de l’équipement}}</label>
									<div class="col-sm-3">
										<input type="text" class="eqLogicAttr form-control" data-l1key="id"
											   style="display : none;"/>
										<input type="text" class="eqLogicAttr form-control" data-l1key="name" id="name"
											   placeholder="{{Nom de l’équipement}}"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-6 control-label" for="sel_object">{{Objet parent}}</label>
									<div class="col-sm-3">
										<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
											<option value="">{{Aucun}}</option>
											<?php foreach (jeeObject::all() as $object) { ?>
												<option value="<?= $object->getId() ?>"><?= $object->getName() ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-6 control-label"></label>
									<div class="col-sm-6">
										<label class="checkbox-inline" for="is-enable">
											<input type="checkbox" class="eqLogicAttr" data-l1key="isEnable"
												   checked="checked" id="is-enable"/>
											{{Activer}}
										</label>
										<label class="checkbox-inline" for="is-visible">
											<input type="checkbox" class="eqLogicAttr" data-l1key="isVisible"
												   checked="checked" id="is-visible"/>
											{{Visible}}
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-6 control-label" for="model">{{Webhooks entrants}}</label>
									<div class="col-sm-6">
										<input type="text" class="eqLogicAttr form-control" id="webhooks"
											   data-l1key="configuration" data-l2key="webhooks"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-6 control-label" for="model">{{Username}}</label>
									<div class="col-sm-6">
										<input type="text" class="eqLogicAttr form-control" id="username" placeholder="Optionel"
											   data-l1key="configuration" data-l2key="username"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-6 control-label" for="model">{{Channel}}</label>
									<div class="col-sm-6">
										<input type="text" class="eqLogicAttr form-control" id="channel" placeholder="Optionel"
											   data-l1key="configuration" data-l2key="channel"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-6 control-label" for="lastCommunication">{{lastCommunication}}</label>
									<div class="col-sm-6">
										<input type="text" disabled="disabled" class="eqLogicAttr form-control" id="lastCommunication"
											   data-l1key="status" data-l2key="lastCommunication"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
			<div id="commandtab" role="tabpanel" class="tab-pane">
				<legend><i class="fa fa-list-alt"></i></i>{{Commande}}</legend>
				<table id="table_command" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th>
							<th>{{Type}}</th>
							<th></th>
							<th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php 
include_file('desktop', $pluginName, 'js', $pluginName);
include_file('core', 'plugin.template', 'js');