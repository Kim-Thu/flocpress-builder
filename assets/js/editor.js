/**
 * Editor tam thoi: chen element + sua JSON + load/save qua REST.
 * Se thay bang React + TypeScript o giai doan sau.
 */
(function () {
  'use strict';

  var config = window.FlocpressEditor;
  var root = document.getElementById('fpb-editor-root');

  if (!config || !root || !window.wp || !window.wp.apiFetch) {
    return;
  }

  var postId = parseInt(root.dataset.postId, 10) || config.postId;

  root.innerHTML =
    '<div class="p-editor__canvas">' +
      '<textarea class="p-editor__json" spellcheck="false"></textarea>' +
      '<div><button type="button" class="button button-primary js-save">Save</button></div>' +
      '<p class="p-editor__status"></p>' +
    '</div>' +
    '<div class="p-editor__panel">' +
      '<strong>Elements</strong>' +
      '<ul class="p-editor__list"></ul>' +
    '</div>';

  var textarea = root.querySelector('.p-editor__json');
  var status = root.querySelector('.p-editor__status');
  var list = root.querySelector('.p-editor__list');
  var button = root.querySelector('.js-save');

  function setStatus(message, state) {
    status.textContent = message;
    status.className = 'p-editor__status' + (state ? ' is-' + state : '');
  }

  function readTree() {
    try {
      var parsed = JSON.parse(textarea.value || '[]');
      return Array.isArray(parsed) ? parsed : [];
    } catch (e) {
      return null;
    }
  }

  function insert(element) {
    var tree = readTree();

    if (tree === null) {
      setStatus('JSON khong hop le', 'error');
      return;
    }

    var settings = {};

    Object.keys(element.controls || {}).forEach(function (key) {
      settings[key] = element.controls[key].default || '';
    });

    var node = { type: element.name, settings: settings };

    if (element.layer === 'l') {
      node.children = [];
    }

    tree.push(node);
    textarea.value = JSON.stringify(tree, null, 2);
    setStatus('', '');
  }

  (config.elements || []).forEach(function (element) {
    var li = document.createElement('li');
    var link = document.createElement('button');
    link.type = 'button';
    link.className = 'button-link';
    link.textContent = element.name + ' (' + element.layer + ')';
    link.addEventListener('click', function () { insert(element); });
    li.appendChild(link);
    list.appendChild(li);
  });

  wp.apiFetch({ path: '/' + config.restNamespace + '/load?post_id=' + postId })
    .then(function (response) {
      textarea.value = JSON.stringify(response.data || [], null, 2);
    })
    .catch(function (error) {
      setStatus(config.i18n.error + ': ' + error.message, 'error');
    });

  button.addEventListener('click', function () {
    var tree = readTree();

    if (tree === null) {
      setStatus('JSON khong hop le', 'error');
      return;
    }

    setStatus(config.i18n.saving, 'saving');
    button.disabled = true;

    wp.apiFetch({
      path: '/' + config.restNamespace + '/save',
      method: 'POST',
      data: { post_id: postId, page_data: tree }
    })
      .then(function (response) {
        textarea.value = JSON.stringify(response.data || [], null, 2);
        setStatus(config.i18n.saved, 'success');
      })
      .catch(function (error) {
        setStatus(config.i18n.error + ': ' + error.message, 'error');
      })
      .finally(function () {
        button.disabled = false;
      });
  });
})();