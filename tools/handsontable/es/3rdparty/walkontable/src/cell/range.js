var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

import CellCoords from './../cell/coords';

/**
 * A cell range is a set of exactly two CellCoords (that can be the same or different)
 *
 * @class CellRange
 */

var CellRange = function () {
  /**
   * @param {CellCoords} highlight Used to draw bold border around a cell where selection was
   *                                          started and to edit the cell when you press Enter
   * @param {CellCoords} from Usually the same as highlight, but in Excel there is distinction - one can change
   *                                     highlight within a selection
   * @param {CellCoords} to End selection
   */
  function CellRange(highlight, from, to) {
    _classCallCheck(this, CellRange);

    this.highlight = highlight;
    this.from = from;
    this.to = to;
  }

  /**
   * Checks if given coords are valid in context of a given Walkontable instance
   *
   * @param {Walkontable} wotInstance
   * @returns {Boolean}
   */


  _createClass(CellRange, [{
    key: 'isValid',
    value: function isValid(wotInstance) {
      return this.from.isValid(wotInstance) && this.to.isValid(wotInstance);
    }

    /**
     * Checks if this cell range is restricted to one cell
     *
     * @returns {Boolean}
     */

  }, {
    key: 'isSingle',
    value: function isSingle() {
      return this.from.row === this.to.row && this.from.col === this.to.col;
    }

    /**
     * Returns selected range height (in number of rows)
     *
     * @returns {Number}
     */

  }, {
    key: 'getHeight',
    value: function getHeight() {
      return Math.max(this.from.row, this.to.row) - Math.min(this.from.row, this.to.row) + 1;
    }

    /**
     * Returns selected range width (in number of columns)
     *
     * @returns {Number}
     */

  }, {
    key: 'getWidth',
    value: function getWidth() {
      return Math.max(this.from.col, this.to.col) - Math.min(this.from.col, this.to.col) + 1;
    }

    /**
     * Checks if given cell coords is within `from` and `to` cell coords of this range
     *
     * @param {CellCoords} cellCoords
     * @returns {Boolean}
     */

  }, {
    key: 'includes',
    value: function includes(cellCoords) {
      var row = cellCoords.row,
          col = cellCoords.col;

      var topLeft = this.getTopLeftCorner();
      var bottomRight = this.getBottomRightCorner();

      return topLeft.row <= row && bottomRight.row >= row && topLeft.col <= col && bottomRight.col >= col;
    }

    /**
     * Checks if given range is within of this range
     *
     * @param {CellRange} testedRange
     * @returns {Boolean}
     */

  }, {
    key: 'includesRange',
    value: function includesRange(testedRange) {
      return this.includes(testedRange.getTopLeftCorner()) && this.includes(testedRange.getBottomRightCorner());
    }

    /**
     * Checks if given range is equal to this range
     *
     * @param {CellRange} testedRange
     * @returns {Boolean}
     */

  }, {
    key: 'isEqual',
    value: function isEqual(testedRange) {
      return Math.min(this.from.row, this.to.row) == Math.min(testedRange.from.row, testedRange.to.row) && Math.max(this.from.row, this.to.row) == Math.max(testedRange.from.row, testedRange.to.row) && Math.min(this.from.col, this.to.col) == Math.min(testedRange.from.col, testedRange.to.col) && Math.max(this.from.col, this.to.col) == Math.max(testedRange.from.col, testedRange.to.col);
    }

    /**
     * Checks if tested range overlaps with the range.
     * Range A is considered to to be overlapping with range B if intersection of A and B or B and A is not empty.
     *
     * @param {CellRange} testedRange
     * @returns {Boolean}
     */

  }, {
    key: 'overlaps',
    value: function overlaps(testedRange) {
      return testedRange.isSouthEastOf(this.getTopLeftCorner()) && testedRange.isNorthWestOf(this.getBottomRightCorner());
    }

    /**
     * @param {CellRange} testedCoords
     * @returns {Boolean}
     */

  }, {
    key: 'isSouthEastOf',
    value: function isSouthEastOf(testedCoords) {
      return this.getTopLeftCorner().isSouthEastOf(testedCoords) || this.getBottomRightCorner().isSouthEastOf(testedCoords);
    }

    /**
     * @param {CellRange} testedCoords
     * @returns {Boolean}
     */

  }, {
    key: 'isNorthWestOf',
    value: function isNorthWestOf(testedCoords) {
      return this.getTopLeftCorner().isNorthWestOf(testedCoords) || this.getBottomRightCorner().isNorthWestOf(testedCoords);
    }

    /**
     * Adds a cell to a range (only if exceeds corners of the range). Returns information if range was expanded
     *
     * @param {CellCoords} cellCoords
     * @returns {Boolean}
     */

  }, {
    key: 'expand',
    value: function expand(cellCoords) {
      var topLeft = this.getTopLeftCorner();
      var bottomRight = this.getBottomRightCorner();

      if (cellCoords.row < topLeft.row || cellCoords.col < topLeft.col || cellCoords.row > bottomRight.row || cellCoords.col > bottomRight.col) {
        this.from = new CellCoords(Math.min(topLeft.row, cellCoords.row), Math.min(topLeft.col, cellCoords.col));
        this.to = new CellCoords(Math.max(bottomRight.row, cellCoords.row), Math.max(bottomRight.col, cellCoords.col));

        return true;
      }

      return false;
    }

    /**
     * @param {CellRange} expandingRange
     * @returns {Boolean}
     */

  }, {
    key: 'expandByRange',
    value: function expandByRange(expandingRange) {
      if (this.includesRange(expandingRange) || !this.overlaps(expandingRange)) {
        return false;
      }

      var topLeft = this.getTopLeftCorner();
      var bottomRight = this.getBottomRightCorner();
      var topRight = this.getTopRightCorner();
      var bottomLeft = this.getBottomLeftCorner();

      var expandingTopLeft = expandingRange.getTopLeftCorner();
      var expandingBottomRight = expandingRange.getBottomRightCorner();

      var resultTopRow = Math.min(topLeft.row, expandingTopLeft.row);
      var resultTopCol = Math.min(topLeft.col, expandingTopLeft.col);
      var resultBottomRow = Math.max(bottomRight.row, expandingBottomRight.row);
      var resultBottomCol = Math.max(bottomRight.col, expandingBottomRight.col);

      var finalFrom = new CellCoords(resultTopRow, resultTopCol),
          finalTo = new CellCoords(resultBottomRow, resultBottomCol);
      var isCorner = new CellRange(finalFrom, finalFrom, finalTo).isCorner(this.from, expandingRange),
          onlyMerge = expandingRange.isEqual(new CellRange(finalFrom, finalFrom, finalTo));

      if (isCorner && !onlyMerge) {
        if (this.from.col > finalFrom.col) {
          finalFrom.col = resultBottomCol;
          finalTo.col = resultTopCol;
        }
        if (this.from.row > finalFrom.row) {
          finalFrom.row = resultBottomRow;
          finalTo.row = resultTopRow;
        }
      }
      this.from = finalFrom;
      this.to = finalTo;

      return true;
    }

    /**
     * @returns {String}
     */

  }, {
    key: 'getDirection',
    value: function getDirection() {
      if (this.from.isNorthWestOf(this.to)) {
        // NorthWest - SouthEast
        return 'NW-SE';
      } else if (this.from.isNorthEastOf(this.to)) {
        // NorthEast - SouthWest
        return 'NE-SW';
      } else if (this.from.isSouthEastOf(this.to)) {
        // SouthEast - NorthWest
        return 'SE-NW';
      } else if (this.from.isSouthWestOf(this.to)) {
        // SouthWest - NorthEast
        return 'SW-NE';
      }
    }

    /**
     * @param {String} direction
     */

  }, {
    key: 'setDirection',
    value: function setDirection(direction) {
      switch (direction) {
        case 'NW-SE':
          var _ref = [this.getTopLeftCorner(), this.getBottomRightCorner()];
          this.from = _ref[0];
          this.to = _ref[1];

          break;
        case 'NE-SW':
          var _ref2 = [this.getTopRightCorner(), this.getBottomLeftCorner()];
          this.from = _ref2[0];
          this.to = _ref2[1];

          break;
        case 'SE-NW':
          var _ref3 = [this.getBottomRightCorner(), this.getTopLeftCorner()];
          this.from = _ref3[0];
          this.to = _ref3[1];

          break;
        case 'SW-NE':
          var _ref4 = [this.getBottomLeftCorner(), this.getTopRightCorner()];
          this.from = _ref4[0];
          this.to = _ref4[1];

          break;
        default:
          break;
      }
    }

    /**
     * Get top left corner of this range
     *
     * @returns {CellCoords}
     */

  }, {
    key: 'getTopLeftCorner',
    value: function getTopLeftCorner() {
      return new CellCoords(Math.min(this.from.row, this.to.row), Math.min(this.from.col, this.to.col));
    }

    /**
     * Get bottom right corner of this range
     *
     * @returns {CellCoords}
     */

  }, {
    key: 'getBottomRightCorner',
    value: function getBottomRightCorner() {
      return new CellCoords(Math.max(this.from.row, this.to.row), Math.max(this.from.col, this.to.col));
    }

    /**
     * Get top right corner of this range
     *
     * @returns {CellCoords}
     */

  }, {
    key: 'getTopRightCorner',
    value: function getTopRightCorner() {
      return new CellCoords(Math.min(this.from.row, this.to.row), Math.max(this.from.col, this.to.col));
    }

    /**
     * Get bottom left corner of this range
     *
     * @returns {CellCoords}
     */

  }, {
    key: 'getBottomLeftCorner',
    value: function getBottomLeftCorner() {
      return new CellCoords(Math.max(this.from.row, this.to.row), Math.min(this.from.col, this.to.col));
    }

    /**
     * @param {CellCoords} coords
     * @param {CellRange} expandedRange
     * @returns {*}
     */

  }, {
    key: 'isCorner',
    value: function isCorner(coords, expandedRange) {
      if (expandedRange) {
        if (expandedRange.includes(coords)) {
          if (this.getTopLeftCorner().isEqual(new CellCoords(expandedRange.from.row, expandedRange.from.col)) || this.getTopRightCorner().isEqual(new CellCoords(expandedRange.from.row, expandedRange.to.col)) || this.getBottomLeftCorner().isEqual(new CellCoords(expandedRange.to.row, expandedRange.from.col)) || this.getBottomRightCorner().isEqual(new CellCoords(expandedRange.to.row, expandedRange.to.col))) {
            return true;
          }
        }
      }

      return coords.isEqual(this.getTopLeftCorner()) || coords.isEqual(this.getTopRightCorner()) || coords.isEqual(this.getBottomLeftCorner()) || coords.isEqual(this.getBottomRightCorner());
    }

    /**
     * @param {CellCoords} coords
     * @param {CellRange} expandedRange
     * @returns {CellCoords}
     */

  }, {
    key: 'getOppositeCorner',
    value: function getOppositeCorner(coords, expandedRange) {
      if (!(coords instanceof CellCoords)) {
        return false;
      }

      if (expandedRange) {
        if (expandedRange.includes(coords)) {
          if (this.getTopLeftCorner().isEqual(new CellCoords(expandedRange.from.row, expandedRange.from.col))) {
            return this.getBottomRightCorner();
          }
          if (this.getTopRightCorner().isEqual(new CellCoords(expandedRange.from.row, expandedRange.to.col))) {
            return this.getBottomLeftCorner();
          }
          if (this.getBottomLeftCorner().isEqual(new CellCoords(expandedRange.to.row, expandedRange.from.col))) {
            return this.getTopRightCorner();
          }
          if (this.getBottomRightCorner().isEqual(new CellCoords(expandedRange.to.row, expandedRange.to.col))) {
            return this.getTopLeftCorner();
          }
        }
      }

      if (coords.isEqual(this.getBottomRightCorner())) {
        return this.getTopLeftCorner();
      } else if (coords.isEqual(this.getTopLeftCorner())) {
        return this.getBottomRightCorner();
      } else if (coords.isEqual(this.getTopRightCorner())) {
        return this.getBottomLeftCorner();
      } else if (coords.isEqual(this.getBottomLeftCorner())) {
        return this.getTopRightCorner();
      }
    }

    /**
     * @param {CellRange} range
     * @returns {Array}
     */

  }, {
    key: 'getBordersSharedWith',
    value: function getBordersSharedWith(range) {
      if (!this.includesRange(range)) {
        return [];
      }

      var thisBorders = {
        top: Math.min(this.from.row, this.to.row),
        bottom: Math.max(this.from.row, this.to.row),
        left: Math.min(this.from.col, this.to.col),
        right: Math.max(this.from.col, this.to.col)
      };
      var rangeBorders = {
        top: Math.min(range.from.row, range.to.row),
        bottom: Math.max(range.from.row, range.to.row),
        left: Math.min(range.from.col, range.to.col),
        right: Math.max(range.from.col, range.to.col)
      };
      var result = [];

      if (thisBorders.top == rangeBorders.top) {
        result.push('top');
      }
      if (thisBorders.right == rangeBorders.right) {
        result.push('right');
      }
      if (thisBorders.bottom == rangeBorders.bottom) {
        result.push('bottom');
      }
      if (thisBorders.left == rangeBorders.left) {
        result.push('left');
      }

      return result;
    }

    /**
     * Get inner selected cell coords defined by this range
     *
     * @returns {Array}
     */

  }, {
    key: 'getInner',
    value: function getInner() {
      var topLeft = this.getTopLeftCorner();
      var bottomRight = this.getBottomRightCorner();
      var out = [];

      for (var r = topLeft.row; r <= bottomRight.row; r++) {
        for (var c = topLeft.col; c <= bottomRight.col; c++) {
          if (!(this.from.row === r && this.from.col === c) && !(this.to.row === r && this.to.col === c)) {
            out.push(new CellCoords(r, c));
          }
        }
      }
      return out;
    }

    /**
     * Get all selected cell coords defined by this range
     *
     * @returns {Array}
     */

  }, {
    key: 'getAll',
    value: function getAll() {
      var topLeft = this.getTopLeftCorner();
      var bottomRight = this.getBottomRightCorner();
      var out = [];

      for (var r = topLeft.row; r <= bottomRight.row; r++) {
        for (var c = topLeft.col; c <= bottomRight.col; c++) {
          if (topLeft.row === r && topLeft.col === c) {
            out.push(topLeft);
          } else if (bottomRight.row === r && bottomRight.col === c) {
            out.push(bottomRight);
          } else {
            out.push(new CellCoords(r, c));
          }
        }
      }

      return out;
    }

    /**
     * Runs a callback function against all cells in the range. You can break the iteration by returning
     * `false` in the callback function
     *
     * @param callback {Function}
     */

  }, {
    key: 'forAll',
    value: function forAll(callback) {
      var topLeft = this.getTopLeftCorner();
      var bottomRight = this.getBottomRightCorner();

      for (var r = topLeft.row; r <= bottomRight.row; r++) {
        for (var c = topLeft.col; c <= bottomRight.col; c++) {
          var breakIteration = callback(r, c);

          if (breakIteration === false) {
            return;
          }
        }
      }
    }
  }]);

  return CellRange;
}();

export default CellRange;