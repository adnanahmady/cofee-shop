# Repositories

### Why using repository classes?

There is a lot of good reasons to use repositories around the project.

- Surly we can separate vary responsibilities using trait's, but after all we are
  prioritizing inheritance over composition and as the result eventually we will face a
  god object that is being loaded everytime and a huge memory usage will be used for
  handling a model methods when we are not going to even use those, and
  besides that we loose flexibility because of all that different methods that we
  load on a model.
- By separating different concerns into independent classes, We will be dealing
  with small, and manageable classes in our code which it means more maintainability.
- By using repository classes you put the model concerns behind a class throughout
  the project and therefore when you need to update a part of the code that is related
  to the model, there will be only one place for you to update. after all, your beloved
  **IDE** may not be around when you need it the most.
- By separating model concerns into a class other than the model itself, you are touching
  the infrastructure base (eloquent orm) as less as possible, and because of this, when
  big upgrades happen for example upgrading the application to a DDD approach-based, your
  job will be a lot easier.
