var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

import BasePlugin from './../_base.js';
import Hooks from './../../pluginHooks';
import SheetClip from './../../../lib/SheetClip/SheetClip';
import { CellCoords, CellRange } from './../../3rdparty/walkontable/src';
import { KEY_CODES, isCtrlKey } from './../../helpers/unicode';
import { getSelectionText } from './../../helpers/dom/element';
import { arrayEach } from './../../helpers/array';
import { rangeEach } from './../../helpers/number';
import { stopImmediatePropagation, stopPropagation, isImmediatePropagationStopped } from './../../helpers/dom/event';
import { registerPlugin } from './../../plugins';
import Textarea from './textarea';
import copyItem from './contextMenuItem/copy';
import cutItem from './contextMenuItem/cut';
import EventManager from './../../eventManager';

Hooks.getSingleton().register('afterCopyLimit');
Hooks.getSingleton().register('modifyCopyableRange');
Hooks.getSingleton().register('beforeCut');
Hooks.getSingleton().register('afterCut');
Hooks.getSingleton().register('beforePaste');
Hooks.getSingleton().register('afterPaste');
Hooks.getSingleton().register('beforeCopy');
Hooks.getSingleton().register('afterCopy');

var ROWS_LIMIT = 1000;
var COLUMNS_LIMIT = 1000;
var privatePool = new WeakMap();

/**
 * @description
 * This plugin enables the copy/paste functionality in the Handsontable.
 *
 * @example
 * ```js
 * ...
 * copyPaste: true,
 * ...
 * ```
 * @class CopyPaste
 * @plugin CopyPaste
 */

var CopyPaste = function (_BasePlugin) {
  _inherits(CopyPaste, _BasePlugin);

  function CopyPaste(hotInstance) {
    _classCallCheck(this, CopyPaste);

    /**
     * Event manager
     *
     * @type {EventManager}
     */
    var _this = _possibleConstructorReturn(this, (CopyPaste.__proto__ || Object.getPrototypeOf(CopyPaste)).call(this, hotInstance));

    _this.eventManager = new EventManager(_this);
    /**
     * Maximum number of columns than can be copied to clipboard using <kbd>CTRL</kbd> + <kbd>C</kbd>.
     *
     * @private
     * @type {Number}
     * @default 1000
     */
    _this.columnsLimit = COLUMNS_LIMIT;
    /**
     * Ranges of the cells coordinates, which should be used to copy/cut/paste actions.
     *
     * @private
     * @type {Array}
     */
    _this.copyableRanges = [];
    /**
     * Defines paste (<kbd>CTRL</kbd> + <kbd>V</kbd>) behavior.
     * * Default value `"overwrite"` will paste clipboard value over current selection.
     * * When set to `"shift_down"`, clipboard data will be pasted in place of current selection, while all selected cells are moved down.
     * * When set to `"shift_right"`, clipboard data will be pasted in place of current selection, while all selected cells are moved right.
     *
     * @private
     * @type {String}
     * @default 'overwrite'
     */
    _this.pasteMode = 'overwrite';
    /**
     * Maximum number of rows than can be copied to clipboard using <kbd>CTRL</kbd> + <kbd>C</kbd>.
     *
     * @private
     * @type {Number}
     * @default 1000
     */
    _this.rowsLimit = ROWS_LIMIT;
    /**
     * The `textarea` element which is necessary to process copying, cutting off and pasting.
     *
     * @private
     * @type {HTMLElement}
     * @default undefined
     */
    _this.textarea = void 0;

    privatePool.set(_this, {
      isTriggeredByPaste: false
    });
    return _this;
  }

  /**
   * Check if plugin is enabled.
   *
   * @returns {Boolean}
   */


  _createClass(CopyPaste, [{
    key: 'isEnabled',
    value: function isEnabled() {
      return !!this.hot.getSettings().copyPaste;
    }

    /**
     * Enable the plugin.
     */

  }, {
    key: 'enablePlugin',
    value: function enablePlugin() {
      var _this2 = this;

      if (this.enabled) {
        return;
      }

      var settings = this.hot.getSettings();

      this.textarea = Textarea.getSingleton();

      if (_typeof(settings.copyPaste) === 'object') {
        this.pasteMode = settings.copyPaste.pasteMode || this.pasteMode;
        this.rowsLimit = settings.copyPaste.rowsLimit || this.rowsLimit;
        this.columnsLimit = settings.copyPaste.columnsLimit || this.columnsLimit;
      }

      this.addHook('afterContextMenuDefaultOptions', function (options) {
        return _this2.onAfterContextMenuDefaultOptions(options);
      });
      this.addHook('beforeKeyDown', function (event) {
        return _this2.onBeforeKeyDown(event);
      });

      this.registerEvents();

      _get(CopyPaste.prototype.__proto__ || Object.getPrototypeOf(CopyPaste.prototype), 'enablePlugin', this).call(this);
    }
    /**
     * Updates the plugin to use the latest options you have specified.
     */

  }, {
    key: 'updatePlugin',
    value: function updatePlugin() {
      this.disablePlugin();
      this.enablePlugin();

      _get(CopyPaste.prototype.__proto__ || Object.getPrototypeOf(CopyPaste.prototype), 'updatePlugin', this).call(this);
    }

    /**
     * Disable plugin for this Handsontable instance.
     */

  }, {
    key: 'disablePlugin',
    value: function disablePlugin() {
      if (this.textarea) {
        this.textarea.destroy();
      }

      _get(CopyPaste.prototype.__proto__ || Object.getPrototypeOf(CopyPaste.prototype), 'disablePlugin', this).call(this);
    }

    /**
     * Prepares copyable text from the cells selection in the invisible textarea.
     *
     * @function setCopyable
     * @memberof CopyPaste#
     */

  }, {
    key: 'setCopyableText',
    value: function setCopyableText() {
      var selRange = this.hot.getSelectedRange();

      if (!selRange) {
        return;
      }

      var topLeft = selRange.getTopLeftCorner();
      var bottomRight = selRange.getBottomRightCorner();
      var startRow = topLeft.row;
      var startCol = topLeft.col;
      var endRow = bottomRight.row;
      var endCol = bottomRight.col;
      var finalEndRow = Math.min(endRow, startRow + this.rowsLimit - 1);
      var finalEndCol = Math.min(endCol, startCol + this.columnsLimit - 1);

      this.copyableRanges.length = 0;

      this.copyableRanges.push({
        startRow: startRow,
        startCol: startCol,
        endRow: finalEndRow,
        endCol: finalEndCol
      });

      this.copyableRanges = this.hot.runHooks('modifyCopyableRange', this.copyableRanges);

      var copyableData = this.getRangedCopyableData(this.copyableRanges);

      this.textarea.setValue(copyableData);

      if (endRow !== finalEndRow || endCol !== finalEndCol) {
        this.hot.runHooks('afterCopyLimit', endRow - startRow + 1, endCol - startCol + 1, this.rowsLimit, this.columnsLimit);
      }
    }

    /**
     * Create copyable text releated to range objects.
     *
     * @since 0.19.0
     * @param {Array} ranges Array of Objects with properties `startRow`, `endRow`, `startCol` and `endCol`.
     * @returns {String} Returns string which will be copied into clipboard.
     */

  }, {
    key: 'getRangedCopyableData',
    value: function getRangedCopyableData(ranges) {
      var _this3 = this;

      var dataSet = [];
      var copyableRows = [];
      var copyableColumns = [];

      // Count all copyable rows and columns
      arrayEach(ranges, function (range) {
        rangeEach(range.startRow, range.endRow, function (row) {
          if (copyableRows.indexOf(row) === -1) {
            copyableRows.push(row);
          }
        });
        rangeEach(range.startCol, range.endCol, function (column) {
          if (copyableColumns.indexOf(column) === -1) {
            copyableColumns.push(column);
          }
        });
      });
      // Concat all rows and columns data defined in ranges into one copyable string
      arrayEach(copyableRows, function (row) {
        var rowSet = [];

        arrayEach(copyableColumns, function (column) {
          rowSet.push(_this3.hot.getCopyableData(row, column));
        });

        dataSet.push(rowSet);
      });

      return SheetClip.stringify(dataSet);
    }

    /**
     * Create copyable text releated to range objects.
     *
     * @since 0.31.1
     * @param {Array} ranges Array of Objects with properties `startRow`, `startCol`, `endRow` and `endCol`.
     * @returns {Array} Returns array of arrays which will be copied into clipboard.
     */

  }, {
    key: 'getRangedData',
    value: function getRangedData(ranges) {
      var _this4 = this;

      var dataSet = [];
      var copyableRows = [];
      var copyableColumns = [];

      // Count all copyable rows and columns
      arrayEach(ranges, function (range) {
        rangeEach(range.startRow, range.endRow, function (row) {
          if (copyableRows.indexOf(row) === -1) {
            copyableRows.push(row);
          }
        });
        rangeEach(range.startCol, range.endCol, function (column) {
          if (copyableColumns.indexOf(column) === -1) {
            copyableColumns.push(column);
          }
        });
      });
      // Concat all rows and columns data defined in ranges into one copyable string
      arrayEach(copyableRows, function (row) {
        var rowSet = [];

        arrayEach(copyableColumns, function (column) {
          rowSet.push(_this4.hot.getCopyableData(row, column));
        });

        dataSet.push(rowSet);
      });

      return dataSet;
    }

    /**
     * Copy action.
     *
     * @param {Boolean} isTriggeredByClick Flag to determine that copy action was executed by the mouse click.
     */

  }, {
    key: 'copy',
    value: function copy(isTriggeredByClick) {
      var rangedData = this.getRangedData(this.copyableRanges);

      var allowCopying = !!this.hot.runHooks('beforeCopy', rangedData, this.copyableRanges);

      if (allowCopying) {
        this.textarea.setValue(SheetClip.stringify(rangedData));
        this.textarea.select();

        if (isTriggeredByClick) {
          document.execCommand('copy');
        }

        this.hot.runHooks('afterCopy', rangedData, this.copyableRanges);
      } else {
        this.textarea.setValue('');
      }
    }

    /**
     * Cut action.
     *
     * @param {Boolean} isTriggeredByClick Flag to determine that cut action was executed by the mouse click.
     */

  }, {
    key: 'cut',
    value: function cut(isTriggeredByClick) {
      var rangedData = this.getRangedData(this.copyableRanges);

      var allowCuttingOut = !!this.hot.runHooks('beforeCut', rangedData, this.copyableRanges);

      if (allowCuttingOut) {
        this.textarea.setValue(SheetClip.stringify(rangedData));
        this.hot.selection.empty();
        this.textarea.select();

        if (isTriggeredByClick) {
          document.execCommand('cut');
        }

        this.hot.runHooks('afterCut', rangedData, this.copyableRanges);
      } else {
        this.textarea.setValue('');
      }
    }

    /**
     * Simulated paste action.
     *
     * @param {String} [value=''] New value, which should be `pasted`.
     */

  }, {
    key: 'paste',
    value: function paste() {
      var value = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

      this.textarea.setValue(value);

      this.onPaste();
      this.onInput();
    }

    /**
     * Register event listeners.
     *
     * @private
     */

  }, {
    key: 'registerEvents',
    value: function registerEvents() {
      var _this5 = this;

      this.eventManager.addEventListener(this.textarea.element, 'paste', function (event) {
        return _this5.onPaste(event);
      });
      this.eventManager.addEventListener(this.textarea.element, 'input', function (event) {
        return _this5.onInput(event);
      });
    }

    /**
     * Trigger to make possible observe `onInput` in textarea.
     *
     * @private
     */

  }, {
    key: 'triggerPaste',
    value: function triggerPaste() {
      this.textarea.select();

      this.onPaste();
    }

    /**
     * `paste` event callback on textarea element.
     *
     * @private
     */

  }, {
    key: 'onPaste',
    value: function onPaste() {
      var priv = privatePool.get(this);

      priv.isTriggeredByPaste = true;
    }

    /**
     * `input` event callback is called after `paste` event callback.
     *
     * @private
     */

  }, {
    key: 'onInput',
    value: function onInput() {
      var _this6 = this;

      var priv = privatePool.get(this);

      if (!this.hot.isListening() || !priv.isTriggeredByPaste) {
        return;
      }

      priv.isTriggeredByPaste = false;

      var input = void 0,
          inputArray = void 0,
          selected = void 0,
          coordsFrom = void 0,
          coordsTo = void 0,
          cellRange = void 0,
          topLeftCorner = void 0,
          bottomRightCorner = void 0,
          areaStart = void 0,
          areaEnd = void 0;

      input = this.textarea.getValue();
      inputArray = SheetClip.parse(input);

      var allowPasting = !!this.hot.runHooks('beforePaste', inputArray, this.copyableRanges);

      if (!allowPasting) {
        return;
      }

      selected = this.hot.getSelected();
      coordsFrom = new CellCoords(selected[0], selected[1]);
      coordsTo = new CellCoords(selected[2], selected[3]);
      cellRange = new CellRange(coordsFrom, coordsFrom, coordsTo);
      topLeftCorner = cellRange.getTopLeftCorner();
      bottomRightCorner = cellRange.getBottomRightCorner();
      areaStart = topLeftCorner;
      areaEnd = new CellCoords(Math.max(bottomRightCorner.row, inputArray.length - 1 + topLeftCorner.row), Math.max(bottomRightCorner.col, inputArray[0].length - 1 + topLeftCorner.col));

      var isSelRowAreaCoverInputValue = coordsTo.row - coordsFrom.row >= inputArray.length - 1;
      var isSelColAreaCoverInputValue = coordsTo.col - coordsFrom.col >= inputArray[0].length - 1;

      this.hot.addHookOnce('afterChange', function (changes, source) {
        var changesLength = changes ? changes.length : 0;

        if (changesLength) {
          var offset = { row: 0, col: 0 };
          var highestColumnIndex = -1;

          arrayEach(changes, function (change, index) {
            var nextChange = changesLength > index + 1 ? changes[index + 1] : null;

            if (nextChange) {
              if (!isSelRowAreaCoverInputValue) {
                offset.row += Math.max(nextChange[0] - change[0] - 1, 0);
              }
              if (!isSelColAreaCoverInputValue && change[1] > highestColumnIndex) {
                highestColumnIndex = change[1];
                offset.col += Math.max(nextChange[1] - change[1] - 1, 0);
              }
            }
          });
          _this6.hot.selectCell(areaStart.row, areaStart.col, areaEnd.row + offset.row, areaEnd.col + offset.col);
        }
      });

      this.hot.populateFromArray(areaStart.row, areaStart.col, inputArray, areaEnd.row, areaEnd.col, 'CopyPaste.paste', this.pasteMode);
      this.hot.runHooks('afterPaste', inputArray, this.copyableRanges);
    }

    /**
     * Add copy, cut and paste options to the Context Menu.
     *
     * @private
     * @param {Object} options Contains default added options of the Context Menu.
     */

  }, {
    key: 'onAfterContextMenuDefaultOptions',
    value: function onAfterContextMenuDefaultOptions(options) {
      options.items.push({
        name: '---------'
      }, copyItem(this), cutItem(this));
    }

    /**
     * beforeKeyDown callback.
     *
     * @private
     * @param {Event} event
     */

  }, {
    key: 'onBeforeKeyDown',
    value: function onBeforeKeyDown(event) {
      var _this7 = this;

      if (!this.hot.getSelected()) {
        return;
      }
      if (this.hot.getActiveEditor() && this.hot.getActiveEditor().isOpened()) {
        return;
      }
      if (isImmediatePropagationStopped(event)) {
        return;
      }
      if (!this.textarea.isActive() && getSelectionText()) {
        return;
      }

      if (isCtrlKey(event.keyCode)) {
        // When fragmentSelection is enabled and some text is selected then don't blur selection calling 'setCopyableText'
        if (this.hot.getSettings().fragmentSelection && getSelectionText()) {
          return;
        }

        // when CTRL is pressed, prepare selectable text in textarea
        this.setCopyableText();
        stopImmediatePropagation(event);

        return;
      }

      // catch CTRL but not right ALT (which in some systems triggers ALT+CTRL)
      var ctrlDown = (event.ctrlKey || event.metaKey) && !event.altKey;

      if (ctrlDown) {
        if (event.keyCode == KEY_CODES.A) {
          setTimeout(function () {
            _this7.setCopyableText();
          }, 0);
        }
        if (event.keyCode == KEY_CODES.X) {
          this.cut();
        }
        if (event.keyCode == KEY_CODES.C) {
          this.copy();
        }
        if (event.keyCode == KEY_CODES.V) {
          this.triggerPaste();
        }
      }
    }

    /**
     * Destroy plugin instance.
     */

  }, {
    key: 'destroy',
    value: function destroy() {
      if (this.textarea) {
        this.textarea.destroy();
      }

      _get(CopyPaste.prototype.__proto__ || Object.getPrototypeOf(CopyPaste.prototype), 'destroy', this).call(this);
    }
  }]);

  return CopyPaste;
}(BasePlugin);

registerPlugin('CopyPaste', CopyPaste);

export default CopyPaste;