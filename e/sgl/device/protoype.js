
function SalesPerson() {
   WorkerBee.call(this);
   this.dept = 'sales';
   this.quota = 100;
}
SalesPerson.prototype = Object.create(WorkerBee.prototype);
SalesPerson.prototype.constructor = SalesPerson;

function Engineer() {
   WorkerBee.call(this);
   this.dept = 'engineering';
   this.machine = '';
}
Engineer.prototype = Object.create(WorkerBee.prototype)
Engineer.prototype.constructor = Engineer;

function Engineer(mach) {
   this.dept = 'engineering';
   this.machine = mach || '';
}
Engineer.prototype = new WorkerBee;

var jim = new Employee;
// Parentheses can be omitted if the
// constructor takes no arguments.
// jim.name is ''
// jim.dept is 'general'

var sally = new Manager;
// sally.name is ''
// sally.dept is 'general'
// sally.reports is []

var mark = new WorkerBee;
// mark.name is ''
// mark.dept is 'general'
// mark.projects is []

var fred = new SalesPerson;
// fred.name is ''
// fred.dept is 'sales'
// fred.projects is []
// fred.quota is 100

var jane = new Engineer;
// jane.name is ''
// jane.dept is 'engineering'
// jane.projects is []
// jane.machine is ''

function Employee() {
  this.dept = 'general';    // Note that this.name (a local variable) does not appear here
}
Employee.prototype.name = '';    // A single copy

function WorkerBee() {
  this.projects = [];
}
WorkerBee.prototype = new Employee;

var amy = new WorkerBee;

Employee.prototype.name = 'Unknown';

function instanceOf(object, constructor) {
   object = object.__proto__;
   while (object != null) {
      if (object == constructor.prototype)
         return true;
      if (typeof object == 'xml') {
        return constructor.prototype == XML.prototype;
      }
      object = object.__proto__;
   }
   return false;
}

instanceOf(chris, Engineer)
instanceOf(chris, WorkerBee)
instanceOf(chris, Employee)
instanceOf(chris, Object)

instanceOf(chris, SalesPerson

//

  var idCounter = 1;

  function Employee(name, dept) {
     this.name = name || '';
     this.dept = dept || 'general';
     this.id = idCounter++;
  }

  function Manager(name, dept, reports) {...}
  Manager.prototype = new Employee;

  function WorkerBee(name, dept, projs) {...}
  WorkerBee.prototype = new Employee;

  function Engineer(name, projs, mach) {...}
  Engineer.prototype = new WorkerBee;

  function SalesPerson(name, projs, quota) {...}
  SalesPerson.prototype = new WorkerBee;

  var mac = new Engineer('Wood, Mac');

  function Employee(name, dept) {
     this.name = name || '';
     this.dept = dept || 'general';
     if (name)
        this.id = idCounter++;
  }
